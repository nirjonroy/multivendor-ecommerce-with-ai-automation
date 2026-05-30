@extends('vendor.layouts.app', ['title' => 'Products'])

@section('page_title', 'Products')

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Product List</h3>
            <a class="btn btn-primary btn-sm ms-auto" href="{{ route('vendor.products.create') }}">Add Product</a>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Approval</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->thumbnail_path)
                                    <img style="width:56px;height:56px;object-fit:contain;border:1px solid #e5e7eb" src="{{ asset('storage/' . $product->thumbnail_path) }}" alt="{{ $product->name }}">
                                @else
                                    <span class="text-secondary">No image</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}<br><small class="text-secondary">{{ $product->sku ?: 'No SKU' }}</small></td>
                            <td>{{ $product->category?->name ?: 'Not set' }}<br><small class="text-secondary">{{ $product->brand?->name ?: 'No brand' }}</small></td>
                            <td>{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</td>
                            <td>{{ $product->stock_quantity > 0 ? $product->stock_quantity : 'Stock Out' }}</td>
                            <td><span class="badge text-bg-{{ $product->status === 'published' ? 'success' : ($product->status === 'draft' ? 'warning' : 'secondary') }}">{{ ucfirst($product->status) }}</span></td>
                            <td>
                                <span class="badge text-bg-{{ $product->approval_status === 'approved' ? 'success' : ($product->approval_status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($product->approval_status ?? 'approved') }}</span>
                                @if($product->approval_rejection_reason)
                                    <br><small class="text-danger">{{ $product->approval_rejection_reason }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-primary" href="{{ route('vendor.products.edit', $product) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('vendor.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4">No vendor products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="card-footer">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
