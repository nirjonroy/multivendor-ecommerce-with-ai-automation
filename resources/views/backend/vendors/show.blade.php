@extends('backend.layouts.app')

@section('title', 'Vendor Review - Admin')
@section('page_title', 'Vendor Review')

@section('page_actions')
    <a class="btn btn-secondary" href="{{ route('admin.vendors.index') }}">Back to Vendors</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5>Shop Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Vendor</dt>
                        <dd class="col-sm-8">{{ $vendor->name }}</dd>
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $vendor->email }}</dd>
                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $vendor->phone ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Shop Name</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_name ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Shop Slug</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_slug ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Shop Email</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_email ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Shop Phone</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_phone ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Shop Address</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_address ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_description ?: 'Not set' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h5>KYC Information</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $vendor->status === 'approved' ? 'success' : ($vendor->status === 'rejected' || $vendor->status === 'suspended' ? 'danger' : 'warning') }}">{{ ucfirst($vendor->status) }}</span>
                    </p>
                    <p>
                        <strong>KYC:</strong>
                        <span class="badge badge-{{ $vendor->kyc_status === 'approved' ? 'success' : ($vendor->kyc_status === 'rejected' ? 'danger' : ($vendor->kyc_status === 'submitted' ? 'info' : 'secondary')) }}">
                            {{ str_replace('_', ' ', ucfirst($vendor->kyc_status ?? 'not_submitted')) }}
                        </span>
                    </p>
                    <dl class="row">
                        <dt class="col-sm-5">Business Type</dt>
                        <dd class="col-sm-7">{{ $vendor->kyc_business_type ?: 'Not set' }}</dd>
                        <dt class="col-sm-5">Registration No.</dt>
                        <dd class="col-sm-7">{{ $vendor->kyc_business_registration_number ?: 'Not set' }}</dd>
                        <dt class="col-sm-5">Tax ID</dt>
                        <dd class="col-sm-7">{{ $vendor->kyc_tax_id ?: 'Not set' }}</dd>
                        <dt class="col-sm-5">NID / Owner ID</dt>
                        <dd class="col-sm-7">{{ $vendor->kyc_nid_number ?: 'Not set' }}</dd>
                        <dt class="col-sm-5">Submitted</dt>
                        <dd class="col-sm-7">{{ $vendor->kyc_submitted_at?->format('d M Y h:i A') ?: 'Not submitted' }}</dd>
                        <dt class="col-sm-5">Reviewed</dt>
                        <dd class="col-sm-7">{{ $vendor->kyc_reviewed_at?->format('d M Y h:i A') ?: 'Not reviewed' }}</dd>
                    </dl>
                    @if($vendor->kyc_document_path)
                        <a class="btn btn-outline-primary btn-sm" target="_blank" href="{{ asset('storage/' . $vendor->kyc_document_path) }}">Open KYC Document</a>
                    @endif
                    @if($vendor->kyc_rejection_reason)
                        <div class="alert alert-danger mt-3 mb-0">{{ $vendor->kyc_rejection_reason }}</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Review Decision</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}" class="mb-3" onsubmit="return confirm('Approve this vendor?')">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-block" type="submit">Approve Vendor</button>
                    </form>

                    <form method="POST" action="{{ route('admin.vendors.reject', $vendor) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <div class="form-group">
                            <label>Reject Reason</label>
                            <textarea class="form-control" name="kyc_rejection_reason" rows="4" required>{{ old('kyc_rejection_reason') }}</textarea>
                        </div>
                        <button class="btn btn-danger btn-block" type="submit">Reject Vendor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
