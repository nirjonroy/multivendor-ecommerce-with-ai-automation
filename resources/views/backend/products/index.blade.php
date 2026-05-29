@extends('backend.layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')

@section('page_actions')
    <a class="btn btn-primary" href="{{ route('admin.products.create') }}">Create Product</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordernone">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Owner</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->thumbnail_path)
                                    <img class="table-img" src="{{ asset('storage/' . $product->thumbnail_path) }}" alt="{{ $product->name }}">
                                @endif
                            </td>
                            <td>{{ $product->name }}<br><small>{{ $product->sku }}</small></td>
                            <td>{{ $product->category?->name }}<br><small>{{ $product->brand?->name }}</small></td>
                            <td>{{ ucfirst($product->owner_type) }} @if($product->vendor) / {{ $product->vendor->name }} @endif</td>
                            <td>{{ number_format($product->offer_price ?: $product->price, 2) }}</td>
                            <td>{{ $product->stock_quantity > 0 ? $product->stock_quantity : 'Stock Out' }}</td>
                            <td>{{ ucfirst($product->status) }}</td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
@endsection
