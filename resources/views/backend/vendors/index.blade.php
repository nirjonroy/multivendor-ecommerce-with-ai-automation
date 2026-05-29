@extends('backend.layouts.app')

@section('title', 'Vendors - Admin')
@section('page_title', 'Vendors')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.vendors.index') }}">All</a>
                @foreach(['pending', 'approved', 'rejected', 'suspended'] as $filter)
                    <a class="btn btn-sm {{ $status === $filter ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.vendors.index', ['status' => $filter]) }}">{{ ucfirst($filter) }}</a>
                @endforeach
            </div>

            <div class="table-responsive">
                <table class="table table-bordernone">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Shop</th>
                            <th>KYC</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td>{{ $vendor->name }}<br><small>{{ $vendor->email }}</small></td>
                                <td>{{ $vendor->shop_name ?: 'Not set' }}<br><small>{{ $vendor->shop_phone ?: $vendor->phone ?: 'No phone' }}</small></td>
                                <td>
                                    <span class="badge badge-{{ $vendor->kyc_status === 'approved' ? 'success' : ($vendor->kyc_status === 'rejected' ? 'danger' : ($vendor->kyc_status === 'submitted' ? 'info' : 'secondary')) }}">
                                        {{ str_replace('_', ' ', ucfirst($vendor->kyc_status ?? 'not_submitted')) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $vendor->status === 'approved' ? 'success' : ($vendor->status === 'rejected' || $vendor->status === 'suspended' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($vendor->status) }}
                                    </span>
                                </td>
                                <td>{{ $vendor->created_at?->format('d M Y') }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.vendors.show', $vendor) }}">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No vendors found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $vendors->links() }}
        </div>
    </div>
@endsection
