@extends('vendor.layouts.app', ['title' => 'Orders'])

@section('page_title', 'Orders')

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Vendor Orders</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Vendor Items</th>
                        <th>Vendor Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $vendorTotal = $order->items->sum('subtotal');
                            $statuses = $order->items->pluck('status')->unique()->values();
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong><br>
                                <small class="text-secondary">{{ $order->transaction_id ?: 'No transaction' }}</small>
                            </td>
                            <td>
                                {{ $order->billing_name }}<br>
                                <small class="text-secondary">{{ $order->billing_phone }}</small>
                            </td>
                            <td>{{ $order->items->sum('quantity') }} item(s)</td>
                            <td>{{ \App\Support\Currency::format($vendorTotal, $globalSiteInfo) }}</td>
                            <td>
                                {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}<br>
                                <span class="badge text-bg-{{ $order->payment_status === 'demo_paid' ? 'success' : 'warning' }}">{{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</span>
                            </td>
                            <td>
                                @foreach($statuses as $status)
                                    <span class="badge text-bg-{{ $status === 'delivered' ? 'success' : ($status === 'cancelled' ? 'danger' : ($status === 'shipped' ? 'info' : 'warning')) }}">{{ ucfirst($status) }}</span>
                                @endforeach
                            </td>
                            <td>{{ $order->created_at?->format('d M Y h:i A') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-primary" href="{{ route('vendor.orders.show', $order) }}">Manage</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('vendor.orders.invoice', $order) }}" target="_blank">Invoice</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4">No orders found for your shop.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="card-footer">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
