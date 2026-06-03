<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderTrackingController extends Controller
{
    public function show(Order $order)
    {
        abort_unless((int) $order->user_id === (int) auth()->id(), 404);

        $order->load(['items.product', 'items.vendor']);

        return view('frontend.dashboard.order-track', compact('order'));
    }

    public function invoice(Order $order)
    {
        abort_unless((int) $order->user_id === (int) auth()->id(), 404);

        $order->load(['items.product', 'items.vendor']);

        return view('frontend.dashboard.invoice', compact('order'));
    }
}
