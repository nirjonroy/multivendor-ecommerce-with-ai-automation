@extends('backend.layouts.app')

@section('title', 'Reports')
@section('page_title', 'Reports')

@push('styles')
    <style>
        .report-filter{background:#fff;border:1px solid #eef1f6;border-radius:8px;padding:22px;box-shadow:0 8px 24px rgba(22,34,51,.06)}
        .report-tabs{display:flex;flex-wrap:wrap;gap:8px;margin:18px 0 24px}
        .report-tabs a{padding:10px 14px;border:1px solid #dbe4ef;border-radius:6px;color:#334155;background:#fff;font-weight:600}
        .report-tabs a.active,.report-tabs a:hover{background:#00baf2;border-color:#00baf2;color:#fff}
        .report-metric{background:#fff;border:1px solid #eef1f6;border-radius:8px;padding:18px;margin-bottom:18px;box-shadow:0 8px 24px rgba(22,34,51,.05)}
        .report-metric span{display:block;color:#64748b;font-size:13px;text-transform:uppercase;font-weight:700}
        .report-metric strong{display:block;color:#111827;font-size:24px;margin-top:6px}
        .report-card{background:#fff;border:0;border-radius:8px;box-shadow:0 8px 24px rgba(22,34,51,.06)}
        .report-card .card-header{display:flex;align-items:center;justify-content:space-between}
        .table td,.table th{vertical-align:middle}
        @media print{.page-sidebar,.page-main-header,.report-filter,.report-tabs,.btn,.breadcrumb,.page-header .text-right{display:none!important}.page-body{margin-left:0!important}.report-card{box-shadow:none}}
    </style>
@endpush

@section('page_actions')
    <button class="btn btn-outline-primary" type="button" onclick="window.print()">Print Report</button>
@endsection

@section('content')
    @php
        $reportTypes = [
            'sales' => 'Sales',
            'orders' => 'Orders',
            'products' => 'Products',
            'admin-products' => 'Admin Products',
            'stock' => 'Stock',
            'vendors' => 'Vendors',
            'customers' => 'Customers',
            'payments' => 'Payments',
        ];
        $dateParams = ['from' => $from->toDateString(), 'to' => $to->toDateString()];
        $badgeClass = fn($status) => $status === 'completed' || $status === 'delivered' || $status === 'approved' || $status === 'demo_paid'
            ? 'success'
            : ($status === 'cancelled' || $status === 'rejected' ? 'danger' : 'warning');
    @endphp

    <form class="report-filter" method="GET" action="{{ route('admin.reports.index') }}">
        <div class="row align-items-end">
            <div class="col-lg-3 col-md-6">
                <label>Report Type</label>
                <select class="form-control" name="type">
                    @foreach($reportTypes as $key => $label)
                        <option value="{{ $key }}" @selected($type === $key)>{{ $label }} Report</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label>From</label>
                <input class="form-control" type="date" name="from" value="{{ $from->toDateString() }}">
            </div>
            <div class="col-lg-3 col-md-6">
                <label>To</label>
                <input class="form-control" type="date" name="to" value="{{ $to->toDateString() }}">
            </div>
            <div class="col-lg-3 col-md-6">
                <button class="btn btn-primary" type="submit">Apply Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.reports.index') }}">Reset</a>
            </div>
        </div>
    </form>

    <div class="report-tabs">
        @foreach($reportTypes as $key => $label)
            <a class="{{ $type === $key ? 'active' : '' }}" href="{{ route('admin.reports.index', array_merge($dateParams, ['type' => $key])) }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6"><div class="report-metric"><span>Sales In Range</span><strong>{{ \App\Support\Currency::format($summary['sales'], $globalSiteInfo) }}</strong></div></div>
        <div class="col-xl-3 col-md-6"><div class="report-metric"><span>Orders In Range</span><strong>{{ number_format($summary['orders']) }}</strong></div></div>
        <div class="col-xl-3 col-md-6"><div class="report-metric"><span>Units Sold</span><strong>{{ number_format($summary['units']) }}</strong></div></div>
        <div class="col-xl-3 col-md-6"><div class="report-metric"><span>Low / Out Stock</span><strong>{{ number_format($summary['low_stock']) }} / {{ number_format($summary['out_of_stock']) }}</strong></div></div>
    </div>

    @if($type === 'sales')
        <div class="card report-card">
            <div class="card-header"><h5>Daily Sales Report</h5><span>{{ $from->format('d M Y') }} - {{ $to->format('d M Y') }}</span></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Date</th><th>Orders</th><th>Subtotal</th><th>Shipping</th><th>Total Sales</th></tr></thead>
                    <tbody>
                    @forelse($dailySales as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->report_date)->format('d M Y') }}</td>
                            <td>{{ number_format($row->orders_total) }}</td>
                            <td>{{ \App\Support\Currency::format($row->subtotal_total, $globalSiteInfo) }}</td>
                            <td>{{ \App\Support\Currency::format($row->shipping_total, $globalSiteInfo) }}</td>
                            <td><strong>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</strong></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No sales found for this range.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'orders')
        <div class="card report-card">
            <div class="card-header"><h5>Order Status Report</h5><a href="{{ route('admin.orders.index') }}">Manage orders</a></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Status</th><th>Orders</th><th>Sales</th></tr></thead>
                    <tbody>
                    @forelse($orderStatusBreakdown as $row)
                        <tr>
                            <td><span class="badge badge-{{ $badgeClass($row->status) }}">{{ ucfirst($row->status) }}</span></td>
                            <td>{{ number_format($row->orders_total) }}</td>
                            <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No orders found for this range.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'products')
        <div class="card report-card">
            <div class="card-header"><h5>Product Performance Report</h5><a href="{{ route('admin.products.index') }}">Manage products</a></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Product</th><th>SKU</th><th>Owner</th><th>Units Sold</th><th>Sales</th></tr></thead>
                    <tbody>
                    @forelse($topProducts as $row)
                        <tr>
                            <td>{{ $row->product_name }}</td>
                            <td>{{ $row->sku ?: 'N/A' }}</td>
                            <td>{{ $row->shop_name ? 'Vendor / '.$row->shop_name : ucfirst($row->owner_type ?: 'admin') }}</td>
                            <td>{{ number_format($row->units_total) }}</td>
                            <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No product sales found for this range.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'admin-products')
        <div class="card report-card">
            <div class="card-header"><h5>Admin Product Sales Report</h5><a href="{{ route('admin.products.create') }}">Add admin product</a></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Product</th><th>SKU</th><th>Units Sold</th><th>Sales</th></tr></thead>
                    <tbody>
                    @forelse($adminProductSales as $row)
                        <tr>
                            <td>{{ $row->product_name }}</td>
                            <td>{{ $row->sku ?: 'N/A' }}</td>
                            <td>{{ number_format($row->units_total) }}</td>
                            <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No admin product sales found for this range.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'stock')
        <div class="card report-card">
            <div class="card-header"><h5>Stock Report</h5><span>Total products: {{ number_format($summary['products']) }}</span></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Product</th><th>Category</th><th>Brand</th><th>Owner</th><th>Stock</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($stockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}<br><small>{{ $product->sku ?: 'No SKU' }}</small></td>
                            <td>{{ $product->category?->name ?: 'N/A' }}</td>
                            <td>{{ $product->brand?->name ?: 'N/A' }}</td>
                            <td>{{ ucfirst($product->owner_type) }} @if($product->vendor) / {{ $product->vendor->shop_name ?: $product->vendor->name }} @endif</td>
                            <td><strong class="{{ $product->stock_quantity <= 0 ? 'text-danger' : ($product->stock_quantity <= 5 ? 'text-warning' : 'text-success') }}">{{ number_format($product->stock_quantity) }}</strong></td>
                            <td>
                                @if($product->stock_quantity <= 0)
                                    <span class="badge badge-danger">Out of stock</span>
                                @elseif($product->stock_quantity <= 5)
                                    <span class="badge badge-warning">Low stock</span>
                                @else
                                    <span class="badge badge-success">In stock</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No products found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'vendors')
        <div class="card report-card">
            <div class="card-header"><h5>Vendor Performance Report</h5><a href="{{ route('admin.vendors.index') }}">Manage vendors</a></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Vendor</th><th>Email</th><th>Status</th><th>KYC</th><th>Orders</th><th>Units</th><th>Sales</th></tr></thead>
                    <tbody>
                    @forelse($vendorPerformance as $vendor)
                        <tr>
                            <td>{{ $vendor->shop_name ?: $vendor->name }}<br><small>{{ $vendor->name }}</small></td>
                            <td>{{ $vendor->email }}</td>
                            <td><span class="badge badge-{{ $badgeClass($vendor->status) }}">{{ ucfirst($vendor->status) }}</span></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $vendor->kyc_status)) }}</td>
                            <td>{{ number_format($vendor->orders_total) }}</td>
                            <td>{{ number_format($vendor->units_total) }}</td>
                            <td>{{ \App\Support\Currency::format($vendor->sales_total, $globalSiteInfo) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No vendors found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'customers')
        <div class="card report-card">
            <div class="card-header"><h5>Customer Report</h5><span>Total customers: {{ number_format($summary['customers']) }}</span></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Customer</th><th>Email</th><th>Phone</th><th>Orders</th><th>Sales</th><th>Last Order</th></tr></thead>
                    <tbody>
                    @forelse($customerPerformance as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?: 'N/A' }}</td>
                            <td>{{ number_format($customer->orders_total) }}</td>
                            <td>{{ \App\Support\Currency::format($customer->sales_total, $globalSiteInfo) }}</td>
                            <td>{{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('d M Y') : 'No orders' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No customers found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'payments')
        <div class="card report-card">
            <div class="card-header"><h5>Payment Report</h5><span>{{ $from->format('d M Y') }} - {{ $to->format('d M Y') }}</span></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Method</th><th>Payment Status</th><th>Orders</th><th>Sales</th></tr></thead>
                    <tbody>
                    @forelse($paymentBreakdown as $row)
                        <tr>
                            <td>{{ strtoupper(str_replace('_', ' ', $row->payment_method)) }}</td>
                            <td><span class="badge badge-{{ $badgeClass($row->payment_status) }}">{{ strtoupper(str_replace('_', ' ', $row->payment_status)) }}</span></td>
                            <td>{{ number_format($row->orders_total) }}</td>
                            <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No payment records found for this range.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
