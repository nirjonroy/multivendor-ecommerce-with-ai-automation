@extends('backend.layouts.app')

@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-4 mb-2"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search order, customer, phone"></div>
                <div class="col-md-3 mb-2">
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        @foreach(['pending', 'processing', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select class="form-control" name="payment_status">
                        <option value="">All Payment</option>
                        @foreach(['pending', 'demo_paid'] as $status)
                            <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2"><button class="btn btn-primary w-100">Filter</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Placed</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong><br><small>{{ $order->transaction_id ?: 'No transaction' }}</small></td>
                            <td>{{ $order->billing_name }}<br><small>{{ $order->billing_email }}<br>{{ $order->billing_phone }}</small></td>
                            <td>{{ $order->items->sum('quantity') }} item(s)</td>
                            <td>{{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}<br><span class="badge badge-{{ $order->payment_status === 'demo_paid' ? 'success' : 'warning' }}">{{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</span></td>
                            <td><span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->created_at?->format('d M Y h:i A') }}</td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.orders.show', $order) }}">Manage</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.invoice', $order) }}" target="_blank">Invoice</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
