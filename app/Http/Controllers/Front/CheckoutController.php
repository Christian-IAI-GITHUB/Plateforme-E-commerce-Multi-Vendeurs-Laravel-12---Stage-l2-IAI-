<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\CustomerOrder;
use App\Models\OrderDetail;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    private function currentSessionId(): string
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', bin2hex(random_bytes(16)));
        }
        return (string) Session::get('cart_session_id');
    }

    public function form(Request $request)
    {
        $items = CartItem::with('product')
            ->when(Auth::check(), fn($q)=>$q->where('user_id', Auth::id()))
            ->when(!Auth::check(), fn($q)=>$q->where('session_id', $this->currentSessionId()))
            ->get();
        if ($items->isEmpty()) {
            return redirect('/cart');
        }
        $total = $items->sum('total_price');
        return view('front.checkout.form', compact('items','total'));
    }

    public function place(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'delivery_address' => 'required|string',
            'payment_method' => 'required|in:flooz,tmoney',
        ]);

        $items = CartItem::with('product')
            ->when(Auth::check(), fn($q)=>$q->where('user_id', Auth::id()))
            ->when(!Auth::check(), fn($q)=>$q->where('session_id', $this->currentSessionId()))
            ->get();
        if ($items->isEmpty()) return redirect('/cart');

        $order = DB::transaction(function() use ($items, $validated) {
            $total = $items->sum('total_price');
            $order = \App\Models\CustomerOrder::create([
                'user_id' => Auth::id(),
                'order_date' => now()->toDateString(),
                'delivery_address' => $validated['delivery_address'],
                'order_status' => 'paid',
                'total_amount' => $total,
                'payment_method' => $validated['payment_method'],
                'phone_number' => $validated['phone_number'],
                'customer_name' => $validated['customer_name'],
            ]);
            foreach($items as $ci){
                \App\Models\OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $ci->product_id,
                    'quantity' => $ci->quantity,
                    'unit_price' => $ci->unit_price,
                    'total_price' => $ci->total_price,
                ]);
            }
            return $order;
        });

        // Vider le panier et rediriger vers le tableau de bord (commande marquée comme payée)
        CartItem::where('user_id', Auth::id())->orWhere('session_id', $this->currentSessionId())->delete();
        return redirect()->route('dashboard');
    }

    public function show($orderId)
    {
        if (Auth::check() && Auth::user()->role === 1) {
            return redirect()->route('vendor.dashboard');
        }

        $order = CustomerOrder::with(['details.product'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('front.checkout.confirmed', compact('order'));
    }
}


