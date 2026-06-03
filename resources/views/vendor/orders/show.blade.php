@extends('vendor.layouts.app', ['title' => 'Order Details'])

@section('page_title', 'Order Details')

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Items For Your Shop</h3>
                    <div class="ms-auto">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('vendor.orders.invoice', $order) }}" target="_blank">Invoice</a>
                        <a class="btn btn-sm btn-secondary" href="{{ route('vendor.orders.index') }}">Back</a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Options</th>
                                <th>Price</th>
                                <th>Current Stock</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Status</th>
                                <th class="text-end">Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product_name }}</strong><br>
                                        <small class="text-secondary">{{ $item->sku ?: 'No SKU' }}</small>
                                    </td>
                                    <td>
                                        Size: {{ $item->size ?: 'N/A' }}<br>
                                        Color: {{ $item->color ?: 'N/A' }}
                                    </td>
                                    <td>{{ \App\Support\Currency::format($item->price, $globalSiteInfo) }}</td>
                                    <td>{{ $item->product?->stock_quantity ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ \App\Support\Currency::format($item->subtotal, $globalSiteInfo) }}</td>
                                    <td>
                                        <span class="badge text-bg-{{ $item->status === 'delivered' ? 'success' : ($item->status === 'cancelled' ? 'danger' : ($item->status === 'shipped' ? 'info' : 'warning')) }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('vendor.order-items.status', $item) }}" class="d-flex gap-2 justify-content-end">
                                            @csrf
                                            @method('PATCH')
                                            <select class="form-select form-select-sm" name="status" style="max-width:150px">
                                                @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                                    <option value="{{ $status }}" @selected($item->status === $status)>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header"><h3 class="card-title">Order Summary</h3></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Order:</strong> {{ $order->order_number }}</p>
                    <p class="mb-1"><strong>Transaction:</strong> {{ $order->transaction_id ?: 'N/A' }}</p>
                    <p class="mb-1"><strong>Payment:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p class="mb-1"><strong>Payment Status:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</p>
                    <p class="mb-1"><strong>Vendor Total:</strong> {{ \App\Support\Currency::format($order->items->sum('subtotal'), $globalSiteInfo) }}</p>
                    <p class="mb-1"><strong>Placed:</strong> {{ $order->created_at?->format('d M Y h:i A') }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="card-title">Customer Billing</h3></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->billing_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->billing_email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $order->billing_phone }}</p>
                    <p class="mb-0"><strong>Address:</strong><br>{{ $order->billing_address }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
