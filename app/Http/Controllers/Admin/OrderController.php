<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        Session::put('page','orders');
        $orders = CustomerOrder::with(['user','details.product'])->orderByDesc('created_at')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(CustomerOrder $order)
    {
        $order->load(['user','details.product']);
        return view('admin.orders.show', compact('order'));
    }
}


