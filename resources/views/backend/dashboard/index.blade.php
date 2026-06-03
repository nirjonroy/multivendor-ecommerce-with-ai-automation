@extends('backend.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('page_actions')
    <a class="btn btn-outline-primary" href="{{ route('home') }}" target="_blank">Visit Website</a>
@endsection

@push('styles')
    <style>
        .dashboard-card{border:0;border-radius:8px;box-shadow:0 8px 24px rgba(22,34,51,.08);overflow:hidden}
        .dashboard-card .card-body{padding:24px}
        .dashboard-metric{display:flex;align-items:center;justify-content:space-between;color:#fff}
        .dashboard-metric h3{font-size:30px;margin:8px 0 0;font-weight:800;color:#fff}
        .dashboard-metric span{font-size:14px;text-transform:uppercase;letter-spacing:.04em}
        .dashboard-metric small{display:block;margin-top:4px;color:rgba(255,255,255,.85);font-size:12px}
        .dashboard-icon{width:54px;height:54px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center}
        .dashboard-icon svg{width:28px;height:28px}
        .bg-products{background:linear-gradient(135deg,#364fb8,#243f9e)}
        .bg-orders{background:linear-gradient(135deg,#00baf2,#009bd1)}
        .bg-sales{background:linear-gradient(135deg,#17c9bd,#0aaa9f)}
        .bg-vendors{background:linear-gradient(135deg,#18ce8c,#14a972)}
        .mini-stat{background:#fff;border:1px solid #eef1f6;border-radius:8px;padding:18px}
        .mini-stat strong{display:block;font-size:22px;color:#1f2937}
        .status-dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:6px}
        .table td,.table th{vertical-align:middle}
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body bg-products dashboard-metric">
                    <div>
                        <span>Products</span>
                        <h3>{{ number_format($stats['products_total']) }}</h3>
                        <small>{{ number_format($stats['products_published']) }} published, {{ number_format($stats['products_pending']) }} pending approval</small>
                    </div>
                    <div class="dashboard-icon"><i data-feather="box"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body bg-orders dashboard-metric">
                    <div>
                        <span>Orders</span>
                        <h3>{{ number_format($stats['orders_total']) }}</h3>
                        <small>{{ number_format($stats['orders_this_month']) }} this month</small>
                    </div>
                    <div class="dashboard-icon"><i data-feather="shopping-cart"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body bg-sales dashboard-metric">
                    <div>
                        <span>Sales This Month</span>
                        <h3>{{ \App\Support\Currency::format($stats['sales_this_month'], $globalSiteInfo) }}</h3>
                        <small>Total sales {{ \App\Support\Currency::format($stats['sales_total'], $globalSiteInfo) }}</small>
                    </div>
                    <div class="dashboard-icon"><i data-feather="trending-up"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body bg-vendors dashboard-metric">
                    <div>
                        <span>Vendors</span>
                        <h3>{{ number_format($stats['vendors_total']) }}</h3>
                        <small>{{ number_format($stats['vendors_pending']) }} pending, {{ number_format($stats['vendors_new_this_month']) }} new this month</small>
                    </div>
                    <div class="dashboard-icon"><i data-feather="users"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3"><div class="mini-stat mb-4"><span>Customers</span><strong>{{ number_format($stats['customers_total']) }}</strong></div></div>
        <div class="col-md-3"><div class="mini-stat mb-4"><span>Published Blogs</span><strong>{{ number_format($stats['blogs_published']) }} / {{ number_format($stats['blogs_total']) }}</strong></div></div>
        <div class="col-md-3"><div class="mini-stat mb-4"><span>Low Stock Items</span><strong>{{ number_format($lowStockProducts->count()) }}</strong></div></div>
        <div class="col-md-3"><div class="mini-stat mb-4"><span>Product Requests</span><strong>{{ number_format($pendingVendorProducts->count()) }}</strong></div></div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header"><h5>Latest Orders</h5></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Placed</th></tr></thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong><br><small>{{ $order->payment_method }}</small></td>
                                    <td>{{ $order->billing_name }}<br><small>{{ $order->billing_email }}</small></td>
                                    <td>{{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</td>
                                    <td><span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header"><h5>Top Selling Products</h5></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Product</th><th>Sold</th><th>Sales</th></tr></thead>
                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ number_format($product->sold_quantity) }}</td>
                                    <td>{{ \App\Support\Currency::format($product->sales_total, $globalSiteInfo) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No sales yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Pending Vendor Products</h5>
                    <a href="{{ route('admin.products.index') }}">View products</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Product</th><th>Vendor</th><th>Price</th><th class="text-right">Action</th></tr></thead>
                        <tbody>
                            @forelse($pendingVendorProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}<br><small>{{ $product->category?->name ?: 'No category' }}</small></td>
                                    <td>{{ $product->vendor?->shop_name ?: $product->vendor?->name }}</td>
                                    <td>{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</td>
                                    <td class="text-right">
                                        <form class="d-inline" method="POST" action="{{ route('admin.products.approve', $product) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <a class="btn btn-sm btn-primary" href="{{ route('admin.products.edit', $product) }}">Review</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No pending product approvals.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Pending Vendors</h5>
                    <a href="{{ route('admin.vendors.index') }}">View vendors</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Shop</th><th>Email</th><th>KYC</th><th class="text-right">Action</th></tr></thead>
                        <tbody>
                            @forelse($pendingVendors as $vendor)
                                <tr>
                                    <td>{{ $vendor->shop_name ?: $vendor->name }}<br><small>{{ $vendor->name }}</small></td>
                                    <td>{{ $vendor->email }}</td>
                                    <td><span class="badge badge-{{ $vendor->kyc_status === 'submitted' ? 'warning' : 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $vendor->kyc_status)) }}</span></td>
                                    <td class="text-right"><a class="btn btn-sm btn-primary" href="{{ route('admin.vendors.show', $vendor) }}">Review</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No pending vendors.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Low Stock Products</h5></div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead><tr><th>Product</th><th>Category</th><th>Owner</th><th>Stock</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}<br><small>{{ $product->sku }}</small></td>
                            <td>{{ $product->category?->name ?: '-' }}</td>
                            <td>{{ ucfirst($product->owner_type) }} @if($product->vendor) / {{ $product->vendor->shop_name ?: $product->vendor->name }} @endif</td>
                            <td><strong class="{{ $product->stock_quantity <= 0 ? 'text-danger' : 'text-warning' }}">{{ $product->stock_quantity <= 0 ? 'Stock Out' : $product->stock_quantity }}</strong></td>
                            <td>{{ ucfirst($product->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No low stock products.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
