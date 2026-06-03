<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Track Order - ' . $order->order_number])
    <style>
        .track-wrap{padding:50px 0}.track-card{background:#fff;border:1px solid #e8edf3;border-radius:8px;padding:26px;box-shadow:0 10px 30px rgba(20,36,58,.05)}
        .track-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:24px 0}
        .track-step{border:1px solid #d9e2ec;border-radius:8px;padding:16px;text-align:center;background:#fbfdff}
        .track-step.active{border-color:#10b9df;background:#eefbff;color:#008db4;font-weight:700}
        .table th{background:#f7fafc}
        @media(max-width:767px){.track-steps{grid-template-columns:1fr}}
    </style>
</head>
<body class="bg-light">
@include('frontend.partials.header')
<section class="track-wrap">
    <div class="container">
        <div class="track-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2>Track Order</h2>
                    <p class="mb-0">{{ $order->order_number }} | {{ $order->created_at?->format('d M Y h:i A') }}</p>
                </div>
                <div>
                    <a class="btn btn-normal" href="{{ route('dashboard.orders.invoice', $order) }}" target="_blank">Invoice</a>
                    <a class="btn btn-outline-secondary" href="{{ route('dashboard.section', 'orders') }}">Back</a>
                </div>
            </div>
            @php($steps = ['pending', 'processing', 'completed'])
            <div class="track-steps">
                <div class="track-step active">Order Placed</div>
                <div class="track-step {{ in_array($order->status, ['processing', 'completed'], true) ? 'active' : '' }}">Processing</div>
                <div class="track-step {{ $order->items->contains('status', 'shipped') || $order->items->contains('status', 'delivered') || $order->status === 'completed' ? 'active' : '' }}">Shipped</div>
                <div class="track-step {{ $order->status === 'completed' || $order->items->every(fn($item) => $item->status === 'delivered') ? 'active' : '' }}">Delivered</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><strong>Payment:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }} / {{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</div>
                <div class="col-md-6 text-md-right"><strong>Total:</strong> {{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Product</th><th>Seller</th><th>Options</th><th>Qty</th><th>Status</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}<br><small>{{ $item->sku }}</small></td>
                                <td>{{ $item->vendor ? ($item->vendor->shop_name ?: $item->vendor->name) : ($globalSiteInfo?->site_name ?? 'Admin') }}</td>
                                <td>Size: {{ $item->size ?: 'N/A' }}<br>Color: {{ $item->color ?: 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>{{ ucfirst($item->status) }}</strong></td>
                                <td>{{ \App\Support\Currency::format($item->subtotal, $globalSiteInfo) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>
