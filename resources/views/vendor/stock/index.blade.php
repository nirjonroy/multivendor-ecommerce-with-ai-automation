@extends('vendor.layouts.app', ['title' => 'Stock Management'])

@section('page_title', 'Stock Management')

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-primary">
                <div class="inner"><h3>{{ $totalStock }}</h3><p>Total Stock Units</p></div>
                <i class="small-box-icon bi bi-boxes"></i>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-warning">
                <div class="inner"><h3>{{ $lowStockCount }}</h3><p>Low Stock Products</p></div>
                <i class="small-box-icon bi bi-exclamation-triangle"></i>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-danger">
                <div class="inner"><h3>{{ $outOfStockCount }}</h3><p>Out Of Stock Products</p></div>
                <i class="small-box-icon bi bi-x-circle"></i>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Product Stock</h3>
        </div>
        <div class="card-body p-0">
            @forelse($products as $product)
                <form method="POST" action="{{ route('vendor.stock.update', $product) }}" class="border-bottom p-3">
                    @csrf
                    @method('PATCH')
                    <div class="row align-items-start g-3">
                        <div class="col-xl-4 col-lg-5">
                            <div class="d-flex gap-3">
                                @if($product->thumbnail_path)
                                    <img src="{{ asset('storage/' . $product->thumbnail_path) }}" alt="{{ $product->name }}" style="width:72px;height:72px;object-fit:contain;border:1px solid #e5e7eb;background:#fff">
                                @else
                                    <div style="width:72px;height:72px;border:1px solid #e5e7eb;background:#f8fafc" class="d-flex align-items-center justify-content-center text-secondary">No image</div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $product->name }}</h5>
                                    <div class="text-secondary small">{{ $product->sku ?: 'No SKU' }}</div>
                                    <div class="text-secondary small">{{ $product->category?->name ?: 'No category' }} / {{ $product->brand?->name ?: 'No brand' }}</div>
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge text-bg-danger mt-2">Out of stock</span>
                                    @elseif($product->stock_quantity <= 5)
                                        <span class="badge text-bg-warning mt-2">Low stock</span>
                                    @else
                                        <span class="badge text-bg-success mt-2">In stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4">
                            <label class="form-label">Base Stock</label>
                            <input class="form-control" type="number" name="stock_quantity" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-8">
                            <label class="form-label">Variation Stock</label>
                            @if($product->variation_stocks)
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead><tr><th>Variation</th><th style="width:120px">Qty</th></tr></thead>
                                        <tbody>
                                            @foreach($product->variation_stocks as $index => $row)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="variation_stocks[{{ $index }}][variation]" value="{{ $row['variation'] ?? '' }}">
                                                        {{ $row['variation'] ?? 'Variation' }}
                                                    </td>
                                                    <td><input class="form-control form-control-sm" type="number" min="0" name="variation_stocks[{{ $index }}][quantity]" value="{{ $row['quantity'] ?? 0 }}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-secondary mb-0">No variation stock rows.</p>
                            @endif
                        </div>
                        <div class="col-xl-2 col-lg-12 text-xl-end">
                            <button class="btn btn-primary" type="submit">Update Stock</button>
                            <a class="btn btn-outline-secondary mt-2 mt-xl-0" href="{{ route('vendor.products.edit', $product) }}">Edit Product</a>
                        </div>
                    </div>
                </form>
            @empty
                <div class="p-4 text-center">No products found.</div>
            @endforelse
        </div>
        @if($products->hasPages())
            <div class="card-footer">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
