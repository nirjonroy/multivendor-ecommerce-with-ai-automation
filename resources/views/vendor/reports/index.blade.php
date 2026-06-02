@extends('vendor.layouts.app', ['title' => 'Reports'])

@section('page_title', 'Reports')

@section('content')
    <form class="card mb-4" method="GET" action="{{ route('vendor.reports.index') }}">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Report Type</label>
                    <select class="form-select" name="type">
                        <option value="sales" @selected($type === 'sales')>Sales Report</option>
                        <option value="products" @selected($type === 'products')>Product Performance</option>
                        <option value="stock" @selected($type === 'stock')>Stock Report</option>
                        <option value="orders" @selected($type === 'orders')>Order Status Report</option>
                        <option value="payments" @selected($type === 'payments')>Payment Report</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input class="form-control" type="date" name="from" value="{{ $from->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input class="form-control" type="date" name="to" value="{{ $to->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit">Apply Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('vendor.reports.index') }}">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner"><h3>{{ \App\Support\Currency::format($summary['sales'], $globalSiteInfo) }}</h3><p>Sales</p></div>
                <i class="small-box-icon bi bi-cash-stack"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner"><h3>{{ $summary['orders'] }}</h3><p>Orders</p></div>
                <i class="small-box-icon bi bi-receipt"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner"><h3>{{ $summary['units'] }}</h3><p>Units Sold</p></div>
                <i class="small-box-icon bi bi-bag-check"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner"><h3>{{ $summary['out_of_stock'] }}</h3><p>Out Of Stock</p></div>
                <i class="small-box-icon bi bi-exclamation-circle"></i>
            </div>
        </div>
    </div>

    @if($type === 'sales')
        <div class="card">
            <div class="card-header"><h3 class="card-title mb-0">Daily Sales</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead><tr><th>Date</th><th>Orders</th><th>Units</th><th>Sales</th></tr></thead>
                    <tbody>
                        @forelse($dailySales as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row->report_date)->format('d M Y') }}</td>
                                <td>{{ $row->orders_total }}</td>
                                <td>{{ $row->units_total }}</td>
                                <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No sales found for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'products')
        <div class="card">
            <div class="card-header"><h3 class="card-title mb-0">Top Products</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead><tr><th>Product</th><th>SKU</th><th>Units Sold</th><th>Sales</th></tr></thead>
                    <tbody>
                        @forelse($topProducts as $row)
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->sku ?: 'N/A' }}</td>
                                <td>{{ $row->units_total }}</td>
                                <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No product sales found for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'stock')
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Stock Report</h3>
                <span class="ms-auto text-secondary">Total stock: {{ $summary['stock'] }} / Low stock: {{ $summary['low_stock'] }}</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead><tr><th>Product</th><th>Category</th><th>Brand</th><th>Stock</th><th>Status</th><th class="text-end">Action</th></tr></thead>
                    <tbody>
                        @forelse($stockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}<br><small class="text-secondary">{{ $product->sku ?: 'No SKU' }}</small></td>
                                <td>{{ $product->category?->name ?: 'N/A' }}</td>
                                <td>{{ $product->brand?->name ?: 'N/A' }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge text-bg-danger">Out of stock</span>
                                    @elseif($product->stock_quantity <= 5)
                                        <span class="badge text-bg-warning">Low stock</span>
                                    @else
                                        <span class="badge text-bg-success">In stock</span>
                                    @endif
                                </td>
                                <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('vendor.stock.index') }}">Manage</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'orders')
        <div class="card">
            <div class="card-header"><h3 class="card-title mb-0">Order Item Status Breakdown</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead><tr><th>Status</th><th>Line Items</th><th>Units</th><th>Sales</th></tr></thead>
                    <tbody>
                        @forelse($statusBreakdown as $row)
                            <tr>
                                <td><span class="badge text-bg-{{ $row->status === 'delivered' ? 'success' : ($row->status === 'cancelled' ? 'danger' : ($row->status === 'shipped' ? 'info' : 'warning')) }}">{{ ucfirst($row->status) }}</span></td>
                                <td>{{ $row->items_count }}</td>
                                <td>{{ $row->units_total }}</td>
                                <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No order items found for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($type === 'payments')
        <div class="card">
            <div class="card-header"><h3 class="card-title mb-0">Payment Breakdown</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead><tr><th>Method</th><th>Payment Status</th><th>Orders</th><th>Sales</th></tr></thead>
                    <tbody>
                        @forelse($paymentBreakdown as $row)
                            <tr>
                                <td>{{ strtoupper(str_replace('_', ' ', $row->payment_method)) }}</td>
                                <td>{{ strtoupper(str_replace('_', ' ', $row->payment_status)) }}</td>
                                <td>{{ $row->orders_total }}</td>
                                <td>{{ \App\Support\Currency::format($row->sales_total, $globalSiteInfo) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No payments found for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
