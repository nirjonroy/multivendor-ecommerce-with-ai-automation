<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $vendor = auth('vendor')->user();

        $orders = Order::query()
            ->with(['user', 'items' => fn ($query) => $query->where('vendor_id', $vendor->id)->with('product')])
            ->whereHas('items', fn ($query) => $query->where('vendor_id', $vendor->id))
            ->latest()
            ->paginate(12);

        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $vendor = auth('vendor')->user();

        abort_unless($order->items()->where('vendor_id', $vendor->id)->exists(), 404);

        $order->load([
            'user',
            'items' => fn ($query) => $query->where('vendor_id', $vendor->id)->with('product'),
        ]);

        return view('vendor.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $vendor = auth('vendor')->user();

        abort_unless($order->items()->where('vendor_id', $vendor->id)->exists(), 404);

        $order->load([
            'user',
            'items' => fn ($query) => $query->where('vendor_id', $vendor->id)->with('product'),
        ]);

        return view('vendor.orders.invoice', compact('order', 'vendor'));
    }

    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $vendor = auth('vendor')->user();

        abort_unless((int) $item->vendor_id === (int) $vendor->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $item->update($data);

        return back()->with('status', 'Order item status updated.');
    }
}
