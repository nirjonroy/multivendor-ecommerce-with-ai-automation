@extends('backend.layouts.app')

@section('title', 'Order Details')
@section('page_title', 'Order Details')

@section('page_actions')
    <a class="btn btn-outline-primary" href="{{ route('admin.orders.invoice', $order) }}" target="_blank">Print Invoice</a>
    <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Back</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h5>Order Items</h5></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Product</th><th>Owner</th><th>Options</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Status</th><th class="text-right">Update</th></tr></thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td><strong>{{ $item->product_name }}</strong><br><small>{{ $item->sku ?: 'No SKU' }}</small></td>
                                    <td>{{ $item->vendor ? 'Vendor / ' . ($item->vendor->shop_name ?: $item->vendor->name) : 'Admin' }}</td>
                                    <td>Size: {{ $item->size ?: 'N/A' }}<br>Color: {{ $item->color ?: 'N/A' }}</td>
                                    <td>{{ \App\Support\Currency::format($item->price, $globalSiteInfo) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ \App\Support\Currency::format($item->subtotal, $globalSiteInfo) }}</td>
                                    <td><span class="badge badge-{{ $item->status === 'delivered' ? 'success' : ($item->status === 'cancelled' ? 'danger' : ($item->status === 'shipped' ? 'info' : 'warning')) }}">{{ ucfirst($item->status) }}</span></td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('admin.order-items.status', $item) }}" class="d-flex justify-content-end">
                                            @csrf
                                            @method('PATCH')
                                            <select class="form-control form-control-sm mr-2" name="status" style="max-width:140px">
                                                @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                                    <option value="{{ $status }}" @selected($item->status === $status)>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-primary">Save</button>
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
            <div class="card mb-4">
                <div class="card-header"><h5>Order Status</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Order Status</label>
                            <select class="form-control" name="status">
                                @foreach(['pending', 'processing', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select class="form-control" name="payment_status">
                                @foreach(['pending', 'demo_paid'] as $status)
                                    <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary">Update Order</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header"><h5>Order Summary</h5></div>
                <div class="card-body">
                    <p><strong>Order:</strong> {{ $order->order_number }}</p>
                    <p><strong>Transaction:</strong> {{ $order->transaction_id ?: 'N/A' }}</p>
                    <p><strong>Payment:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p><strong>Subtotal:</strong> {{ \App\Support\Currency::format($order->subtotal, $globalSiteInfo) }}</p>
                    <p><strong>Shipping:</strong> {{ \App\Support\Currency::format($order->shipping_amount, $globalSiteInfo) }}</p>
                    <p><strong>Total:</strong> {{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</p>
                    <p class="mb-0"><strong>Placed:</strong> {{ $order->created_at?->format('d M Y h:i A') }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5>Customer Billing</h5></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->billing_name }}</p>
                    <p><strong>Email:</strong> {{ $order->billing_email }}</p>
                    <p><strong>Phone:</strong> {{ $order->billing_phone }}</p>
                    <p class="mb-0"><strong>Address:</strong><br>{{ $order->billing_address }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
