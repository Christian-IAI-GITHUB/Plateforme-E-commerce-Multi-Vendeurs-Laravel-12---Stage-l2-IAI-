<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function currentSessionId(Request $request): string
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', bin2hex(random_bytes(16)));
        }
        return Session::get('cart_session_id');
    }

    public function count(Request $request)
    {
        $query = CartItem::query();
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->currentSessionId($request));
        }
        // Afficher le nombre de produits distincts dans le panier (et non la quantité totale)
        $count = (int) $query->distinct('product_id')->count('product_id');
        return response()->json(['count' => $count]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $product = Product::findOrFail($request->product_id);
        $quantity = max(1, (int) $request->input('quantity', 1));

        $attrs = [
            'product_id' => $product->id,
        ];
        if (Auth::check()) {
            $attrs['user_id'] = Auth::id();
        } else {
            $attrs['session_id'] = $this->currentSessionId($request);
        }

        $item = CartItem::where($attrs)->first();
        if ($item) {
            $item->quantity += $quantity;
        } else {
            $item = new CartItem($attrs);
            $item->quantity = $quantity;
            $item->unit_price = $product->final_price ?? $product->product_price;
        }
        $item->total_price = $item->quantity * $item->unit_price;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier avec succès !',
            'product_name' => $product->product_name
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:0',
        ]);
        $item = CartItem::findOrFail($request->id);
        if ($request->quantity <= 0) {
            $item->delete();
            return response()->json(['ok' => true]);
        }
        $item->quantity = $request->quantity;
        $item->total_price = $item->quantity * $item->unit_price;
        $item->save();
        return response()->json(['ok' => true]);
    }

    public function remove(Request $request)
    {
        $request->validate(['id' => 'required|exists:cart_items,id']);
        CartItem::where('id', $request->id)->delete();
        return response()->json(['ok' => true]);
    }

    public function index(Request $request)
    {
        $query = CartItem::with('product');
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->currentSessionId($request));
        }
        $items = $query->get();
        $total = $items->sum('total_price');
        return view('front.cart.index', compact('items', 'total'));
    }
}


