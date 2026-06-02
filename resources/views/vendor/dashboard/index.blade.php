@extends('vendor.layouts.app', ['title' => 'Vendor Dashboard'])

@section('page_title', 'Vendor Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner"><h3>{{ $totalProducts }}</h3><p>Total Products</p></div>
                <i class="small-box-icon bi bi-box-seam-fill"></i>
                <a href="{{ route('vendor.products.index') }}" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner"><h3>{{ $publishedProducts }}</h3><p>Published Products</p></div>
                <i class="small-box-icon bi bi-check-circle-fill"></i>
                <a href="{{ route('vendor.products.index') }}" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner"><h3>{{ $draftProducts }}</h3><p>Draft Products</p></div>
                <i class="small-box-icon bi bi-pencil-square"></i>
                <a href="{{ route('vendor.products.index') }}" class="small-box-footer link-dark">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner"><h3>{{ $totalOrders }}</h3><p>Total Orders</p></div>
                <i class="small-box-icon bi bi-receipt"></i>
                <a href="{{ route('vendor.orders.index') }}" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info">
                <div class="inner"><h3>{{ $stockQuantity }}</h3><p>Total Stock</p></div>
                <i class="small-box-icon bi bi-boxes"></i>
                <a href="{{ route('vendor.stock.index') }}" class="small-box-footer link-light">Manage Stock <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">Latest Products</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                        <thead><tr><th>Product</th><th>SKU</th><th>Status</th><th>Approval</th><th>Stock</th><th>Price</th></tr></thead>
                        <tbody>
                            @forelse($latestProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku ?: 'N/A' }}</td>
                                    <td><span class="badge text-bg-{{ $product->status === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span></td>
                                    <td><span class="badge text-bg-{{ $product->approval_status === 'approved' ? 'success' : ($product->approval_status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($product->approval_status ?? 'approved') }}</span></td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4">No vendor products yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-success card-outline mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Latest Orders</h3>
                    <a class="btn btn-sm btn-primary ms-auto" href="{{ route('vendor.orders.index') }}">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('vendor.orders.show', $order) }}">{{ $order->order_number }}</a><br>
                                        <small class="text-secondary">{{ $order->billing_name }}</small>
                                    </td>
                                    <td class="text-end">{{ \App\Support\Currency::format($order->items->sum('subtotal'), $globalSiteInfo) }}</td>
                                </tr>
                            @empty
                                <tr><td class="text-center py-3">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-primary card-outline mb-4">
                <div class="card-header"><h3 class="card-title">Shop Profile</h3></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Vendor:</strong> {{ $vendor->name }}</p>
                    <p class="mb-1"><strong>Shop:</strong> {{ $vendor->shop_name ?: 'Not set' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $vendor->shop_email ?: $vendor->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $vendor->shop_phone ?: $vendor->phone ?: 'Not set' }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $vendor->shop_address ?: 'Not set' }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($vendor->status) }}</p>
                    <p class="mb-1"><strong>KYC:</strong> {{ str_replace('_', ' ', ucfirst($vendor->kyc_status ?? 'not_submitted')) }}</p>
                    <a class="btn btn-primary btn-sm mt-3" href="{{ route('vendor.shop-settings.edit') }}">Edit Shop Settings</a>
                </div>
            </div>
            <div class="card card-info card-outline mb-4">
                <div class="card-header"><h3 class="card-title">Admin Support</h3></div>
                <form method="POST" action="{{ route('vendor.support-message.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Support subject">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="4" required placeholder="Write your message to admin">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">Contact Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
