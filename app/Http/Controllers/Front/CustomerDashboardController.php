<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role === 1) {
            return redirect()->route('vendor.dashboard');
        }

        $userId = Auth::id();
        $orders = CustomerOrder::with(['details.product'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total' => $orders->count(),
            'pending' => $orders->where('order_status', 'pending')->count(),
            'paid' => $orders->where('order_status', 'paid')->count(),
            'member_since' => optional(Auth::user()->created_at)->format('d/m/Y'),
        ];

        return view('front.account.dashboard', compact('orders', 'stats'));
    }
}


