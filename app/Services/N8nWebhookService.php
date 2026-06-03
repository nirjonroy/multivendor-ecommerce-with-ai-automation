<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class N8nWebhookService
{
    public function sendProductCreated(Product $product): void
    {
        $webhookUrl = Setting::get('n8n_product_webhook');

        if (! $webhookUrl) {
            return;
        }

        try {
            Http::timeout(20)->post($webhookUrl, [
                'event' => 'product.created',
                'callback_url' => route('api.n8n.product-callback'),
                'product' => $this->productPayload($product),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    public function checkOrder(Order $order): array
    {
        $webhookUrl = Setting::get('n8n_order_webhook');

        if (! $webhookUrl) {
            return [
                'ok' => false,
                'message' => 'n8n order webhook URL is not configured.',
            ];
        }

        try {
            $response = Http::timeout(20)->post($webhookUrl, [
                'event' => 'order.check',
                'order' => $this->orderPayload($order),
            ]);
        } catch (\Throwable $exception) {
            report($exception);

            return [
                'ok' => false,
                'message' => 'n8n request failed: ' . $exception->getMessage(),
            ];
        }

        return [
            'ok' => $response->successful(),
            'message' => $response->successful()
                ? 'Order check request sent to n8n successfully.'
                : 'n8n rejected the order check request. HTTP status: ' . $response->status(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    private function productPayload(Product $product): array
    {
        $product->loadMissing(['category', 'subCategory', 'childCategory', 'brand', 'vendor']);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'category' => $product->category?->name,
            'sub_category' => $product->subCategory?->name,
            'child_category' => $product->childCategory?->name,
            'brand' => $product->brand?->name,
            'owner_type' => $product->owner_type,
            'vendor' => $product->vendor?->shop_name ?: $product->vendor?->name,
            'price' => $product->price,
            'offer_price' => $product->offer_price,
            'stock_quantity' => $product->stock_quantity,
            'short_description' => $product->short_description,
            'long_description' => $product->long_description,
            'status' => $product->status,
            'approval_status' => $product->approval_status,
        ];
    }

    private function orderPayload(Order $order): array
    {
        $order->loadMissing(['user', 'items.product', 'items.vendor']);

        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'transaction_id' => $order->transaction_id,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
            'shipping_status' => $order->shipping_status,
            'courier_tracking_id' => $order->courier_tracking_id,
            'subtotal' => $order->subtotal,
            'shipping_amount' => $order->shipping_amount,
            'total' => $order->total,
            'customer' => [
                'id' => $order->user?->id,
                'name' => $order->billing_name,
                'email' => $order->billing_email,
                'phone' => $order->billing_phone,
                'address' => $order->billing_address,
            ],
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'sku' => $item->sku,
                'vendor' => $item->vendor?->shop_name ?: $item->vendor?->name,
                'size' => $item->size,
                'color' => $item->color,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'status' => $item->status,
            ])->values()->all(),
        ];
    }
}
