<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    public function sendToCourier(Order $order)
    {
        if ($order->courier_tracking_id) {
            return back()->with('status', 'This order is already sent to courier.');
        }

        $apiKey = config('services.steadfast.api_key');
        $secretKey = config('services.steadfast.secret_key');
        $baseUrl = rtrim((string) config('services.steadfast.base_url'), '/');

        if (! $apiKey || ! $secretKey || ! $baseUrl) {
            return back()->withErrors('Steadfast courier credentials are missing.');
        }

        $endpoint = $baseUrl . '/create_order';

        $payload = [
            'invoice' => $order->order_number,
            'recipient_name' => $order->billing_name,
            'recipient_phone' => $order->billing_phone,
            'recipient_address' => $order->billing_address,
            'cod_amount' => (float) $order->total,
            'note' => 'Order placed from admin panel',
        ];

        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
                'Accept' => 'application/json',
            ])->timeout(20)->post($endpoint, $payload);
        } catch (\Throwable $exception) {
            return back()->withErrors('Steadfast request failed: ' . $exception->getMessage());
        }

        if ($response->status() !== 200) {
            return back()->withErrors('Steadfast rejected the order. HTTP status: ' . $response->status());
        }

        $body = $response->json();
        $trackingId = data_get($body, 'consignment.tracking_code')
            ?? data_get($body, 'data.consignment.tracking_code')
            ?? data_get($body, 'data.tracking_code')
            ?? data_get($body, 'tracking_code')
            ?? data_get($body, 'consignment_id')
            ?? data_get($body, 'data.consignment_id');

        if (! $trackingId) {
            return back()->withErrors('Steadfast accepted the request but no tracking code was returned.');
        }

        $order->update([
            'courier_tracking_id' => $trackingId,
            'shipping_status' => 'sent_to_courier',
        ]);

        return back()->with('status', 'Order sent to Steadfast Courier. Tracking ID: ' . $trackingId);
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product', 'items.vendor']);

        return view('backend.orders.invoice', compact('order'));
    }
}
