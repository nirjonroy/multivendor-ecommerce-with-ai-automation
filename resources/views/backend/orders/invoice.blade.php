<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <style>
        body{background:#f7f9fc;color:#17202a}.invoice-wrap{max-width:980px;margin:30px auto;background:#fff;padding:36px;border:1px solid #e5eaf0}
        .invoice-head{display:flex;justify-content:space-between;border-bottom:2px solid #eef2f6;padding-bottom:18px;margin-bottom:24px}
        .invoice-logo{max-width:180px;max-height:70px;object-fit:contain}.table th{background:#f8fafc}.print-actions{text-align:right;margin:20px auto;max-width:980px}
        @media print{body{background:#fff}.print-actions{display:none}.invoice-wrap{margin:0;border:0;max-width:100%}}
    </style>
</head>
<body>
<div class="print-actions">
    <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.orders.show', $order) }}">Back</a>
</div>
<div class="invoice-wrap">
    <div class="invoice-head">
        <div>
            <img class="invoice-logo" src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" alt="{{ $globalSiteInfo?->site_name ?? 'Multivendor Ecommerce' }}">
            <h4 class="mt-3">{{ $globalSiteInfo?->site_name ?? 'Multivendor Ecommerce' }}</h4>
            <p class="mb-0">{{ $globalSiteInfo?->address }}</p>
            <p class="mb-0">{{ $globalSiteInfo?->contact_email }} {{ $globalSiteInfo?->contact_phone ? ' | ' . $globalSiteInfo->contact_phone : '' }}</p>
        </div>
        <div class="text-right">
            <h2>Invoice</h2>
            <p class="mb-1"><strong>Order:</strong> {{ $order->order_number }}</p>
            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at?->format('d M Y h:i A') }}</p>
            <p class="mb-0"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Bill To</h5>
            <p class="mb-1">{{ $order->billing_name }}</p>
            <p class="mb-1">{{ $order->billing_email }}</p>
            <p class="mb-1">{{ $order->billing_phone }}</p>
            <p class="mb-0">{{ $order->billing_address }}</p>
        </div>
        <div class="col-md-6 text-md-right">
            <h5>Payment</h5>
            <p class="mb-1">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p class="mb-1">{{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</p>
            <p class="mb-0">{{ $order->transaction_id ?: 'No transaction' }}</p>
        </div>
    </div>
    <table class="table table-bordered">
        <thead><tr><th>Product</th><th>Owner</th><th>Options</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}<br><small>{{ $item->sku }}</small></td>
                    <td>{{ $item->vendor ? ($item->vendor->shop_name ?: $item->vendor->name) : 'Admin' }}</td>
                    <td>Size: {{ $item->size ?: 'N/A' }}<br>Color: {{ $item->color ?: 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ \App\Support\Currency::format($item->price, $globalSiteInfo) }}</td>
                    <td>{{ \App\Support\Currency::format($item->subtotal, $globalSiteInfo) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr><th colspan="5" class="text-right">Subtotal</th><th>{{ \App\Support\Currency::format($order->subtotal, $globalSiteInfo) }}</th></tr>
            <tr><th colspan="5" class="text-right">Shipping</th><th>{{ \App\Support\Currency::format($order->shipping_amount, $globalSiteInfo) }}</th></tr>
            <tr><th colspan="5" class="text-right">Total</th><th>{{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</th></tr>
        </tfoot>
    </table>
</div>
</body>
</html>
