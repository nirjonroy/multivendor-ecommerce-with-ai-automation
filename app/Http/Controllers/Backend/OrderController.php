<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with(['user', 'items.vendor'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->payment_status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('order_number', 'like', $term)
                        ->orWhere('transaction_id', 'like', $term)
                        ->orWhere('billing_name', 'like', $term)
                        ->orWhere('billing_email', 'like', $term)
                        ->orWhere('billing_phone', 'like', $term);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.vendor']);

        return view('backend.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['demo_paid', 'pending'])],
        ]);

        $order->update($data);

        return back()->with('status', 'Order updated successfully.');
    }

    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
        ]);

        $item->update($data);

        return back()->with('status', 'Order item status updated.');
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product', 'items.vendor']);

        return view('backend.orders.invoice', compact('order'));
    }
}
