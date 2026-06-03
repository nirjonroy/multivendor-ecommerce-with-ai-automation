<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Invoice - ' . $order->order_number])
    <style>
        body{background:#f7fafc;color:#1f2d3d}
        .invoice-wrap{max-width:980px;margin:32px auto;background:#fff;border:1px solid #e3e8ef;border-radius:8px;padding:32px}
        .invoice-head{display:flex;justify-content:space-between;gap:24px;border-bottom:2px solid #eef2f6;padding-bottom:20px;margin-bottom:24px}
        .invoice-logo{max-width:190px;max-height:72px;object-fit:contain}
        .invoice-title{text-align:right}
        .invoice-title h1{font-size:30px;margin:0 0 8px;text-transform:uppercase;color:#172033}
        .invoice-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-bottom:24px}
        .invoice-box{border:1px solid #e8edf3;border-radius:6px;padding:16px;background:#fbfdff}
        .invoice-box h3{font-size:16px;margin-bottom:10px;color:#172033;text-transform:none}
        .invoice-table{width:100%;border-collapse:collapse;margin-bottom:18px}
        .invoice-table th,.invoice-table td{border-bottom:1px solid #e8edf3;padding:12px;text-align:left;vertical-align:top}
        .invoice-table th{background:#f7fafc;color:#334155}
        .invoice-total{max-width:360px;margin-left:auto}
        .invoice-total div{display:flex;justify-content:space-between;border-bottom:1px solid #e8edf3;padding:10px 0}
        .invoice-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:24px}
        .invoice-actions .btn{border-radius:4px}
        @media(max-width:767px){.invoice-head,.invoice-grid{display:block}.invoice-title{text-align:left;margin-top:18px}.invoice-wrap{margin:12px;padding:18px}}
        @media print{.invoice-actions,.theme-layout,.color-picker{display:none!important}body{background:#fff}.invoice-wrap{border:0;margin:0;max-width:none}}
    </style>
</head>
<body>
@php
    $logo = $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png');
@endphp
<main class="invoice-wrap">
    <div class="invoice-head">
        <div>
            <img class="invoice-logo" src="{{ $logo }}" alt="{{ $globalSiteInfo?->site_name ?? 'Store' }}">
            <p class="mt-3 mb-1">{{ $globalSiteInfo?->address }}</p>
            <p class="mb-1">{{ $globalSiteInfo?->contact_phone }}</p>
            <p class="mb-0">{{ $globalSiteInfo?->contact_email }}</p>
        </div>
        <div class="invoice-title">
            <h1>Invoice</h1>
            <p class="mb-1"><strong>Order:</strong> {{ $order->order_number }}</p>
            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at?->format('d M Y h:i A') }}</p>
            <p class="mb-0"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
    </div>

    <div class="invoice-grid">
        <div class="invoice-box">
            <h3>Billing Details</h3>
            <p class="mb-1"><strong>Name:</strong> {{ $order->billing_name }}</p>
            <p class="mb-1"><strong>Email:</strong> {{ $order->billing_email }}</p>
            <p class="mb-1"><strong>Phone:</strong> {{ $order->billing_phone }}</p>
            <p class="mb-0"><strong>Address:</strong><br>{{ $order->billing_address }}</p>
        </div>
        <div class="invoice-box">
            <h3>Payment Details</h3>
            <p class="mb-1"><strong>Method:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p class="mb-1"><strong>Payment Status:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</p>
            <p class="mb-0"><strong>Transaction:</strong> {{ $order->transaction_id ?: 'N/A' }}</p>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Seller</th>
            <th>Options</th>
            <th>Status</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}<br><small>{{ $item->sku ?: 'No SKU' }}</small></td>
                <td>{{ $item->vendor ? ($item->vendor->shop_name ?: $item->vendor->name) : ($globalSiteInfo?->site_name ?? 'Admin') }}</td>
                <td>Size: {{ $item->size ?: 'N/A' }}<br>Color: {{ $item->color ?: 'N/A' }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ \App\Support\Currency::format($item->price, $globalSiteInfo) }}</td>
                <td>{{ \App\Support\Currency::format($item->subtotal, $globalSiteInfo) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="invoice-total">
        <div><span>Subtotal</span><strong>{{ \App\Support\Currency::format($order->subtotal, $globalSiteInfo) }}</strong></div>
        <div><span>Shipping</span><strong>{{ \App\Support\Currency::format($order->shipping_amount, $globalSiteInfo) }}</strong></div>
        <div><span>Total</span><strong>{{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</strong></div>
    </div>

    <div class="invoice-actions">
        <a class="btn btn-outline-secondary" href="{{ route('dashboard.orders.show', $order) }}">Back To Tracking</a>
        <button class="btn btn-normal" type="button" onclick="window.print()">Print Invoice</button>
    </div>
</main>
</body>
</html>
