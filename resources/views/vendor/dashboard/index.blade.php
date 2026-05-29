@extends('vendor.layouts.app', ['title' => 'Vendor Dashboard'])

@section('page_title', 'Vendor Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner"><h3>{{ $totalProducts }}</h3><p>Total Products</p></div>
                <i class="small-box-icon bi bi-box-seam-fill"></i>
                <a href="#" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner"><h3>{{ $publishedProducts }}</h3><p>Published Products</p></div>
                <i class="small-box-icon bi bi-check-circle-fill"></i>
                <a href="#" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner"><h3>{{ $draftProducts }}</h3><p>Draft Products</p></div>
                <i class="small-box-icon bi bi-pencil-square"></i>
                <a href="#" class="small-box-footer link-dark">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner"><h3>{{ $stockQuantity }}</h3><p>Total Stock</p></div>
                <i class="small-box-icon bi bi-layers-fill"></i>
                <a href="#" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">Latest Products</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                        <thead><tr><th>Product</th><th>SKU</th><th>Status</th><th>Stock</th><th>Price</th></tr></thead>
                        <tbody>
                            @forelse($latestProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku ?: 'N/A' }}</td>
                                    <td><span class="badge text-bg-{{ $product->status === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span></td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4">No vendor products yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
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
        </div>
    </div>
@endsection
