@extends('vendor.layouts.app', ['title' => 'Shop Settings'])

@section('page_title', 'Shop Settings')

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header"><h3 class="card-title">Shop Details</h3></div>
                <form method="POST" action="{{ route('vendor.shop-settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Shop Name <span class="text-danger">*</span></label>
                            <input class="form-control" name="shop_name" value="{{ old('shop_name', $vendor->shop_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shop Slug</label>
                            <input class="form-control" name="shop_slug" value="{{ old('shop_slug', $vendor->shop_slug) }}" placeholder="Auto generated from shop name if empty">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shop Email</label>
                                <input class="form-control" type="email" name="shop_email" value="{{ old('shop_email', $vendor->shop_email) }}" placeholder="{{ $vendor->email }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shop Phone</label>
                                <input class="form-control" name="shop_phone" value="{{ old('shop_phone', $vendor->shop_phone) }}" placeholder="{{ $vendor->phone }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shop Address</label>
                            <textarea class="form-control" name="shop_address" rows="3">{{ old('shop_address', $vendor->shop_address) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shop Description</label>
                            <textarea class="form-control" name="shop_description" rows="5">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                        </div>
                        <hr>
                        <h5 class="mb-3">KYC Information</h5>
                        @if($vendor->kyc_status === 'rejected' && $vendor->kyc_rejection_reason)
                            <div class="alert alert-danger">
                                <strong>KYC rejected:</strong> {{ $vendor->kyc_rejection_reason }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Type</label>
                                <select class="form-select" name="kyc_business_type">
                                    <option value="">Select Business Type</option>
                                    @foreach(['Individual', 'Sole Proprietorship', 'Partnership', 'Company'] as $type)
                                        <option value="{{ $type }}" @selected(old('kyc_business_type', $vendor->kyc_business_type) === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Registration Number</label>
                                <input class="form-control" name="kyc_business_registration_number" value="{{ old('kyc_business_registration_number', $vendor->kyc_business_registration_number) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax ID / BIN</label>
                                <input class="form-control" name="kyc_tax_id" value="{{ old('kyc_tax_id', $vendor->kyc_tax_id) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NID / Owner ID Number</label>
                                <input class="form-control" name="kyc_nid_number" value="{{ old('kyc_nid_number', $vendor->kyc_nid_number) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KYC Document</label>
                            <input class="form-control" type="file" name="kyc_document" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Upload trade license, NID, tax certificate, or another verification document. Accepted: JPG, PNG, PDF up to 4MB.</div>
                            @if($vendor->kyc_document_path)
                                <a class="btn btn-outline-secondary btn-sm mt-2" target="_blank" href="{{ asset('storage/' . $vendor->kyc_document_path) }}">View Current Document</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">Shop Overview</h3></div>
                <div class="card-body">
                    <h4 class="mb-1">{{ $vendor->shop_name ?: 'Vendor Shop' }}</h4>
                    <p class="text-secondary mb-3">{{ $vendor->shop_description ?: 'No shop description added yet.' }}</p>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Owner</dt>
                        <dd class="col-sm-8">{{ $vendor->name }}</dd>
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8"><span class="badge text-bg-{{ $vendor->status === 'approved' ? 'success' : (in_array($vendor->status, ['rejected', 'suspended'], true) ? 'danger' : 'warning') }}">{{ ucfirst($vendor->status) }}</span></dd>
                        <dt class="col-sm-4">KYC</dt>
                        <dd class="col-sm-8"><span class="badge text-bg-{{ $vendor->kyc_status === 'approved' ? 'success' : ($vendor->kyc_status === 'rejected' ? 'danger' : ($vendor->kyc_status === 'submitted' ? 'info' : 'secondary')) }}">{{ str_replace('_', ' ', ucfirst($vendor->kyc_status ?? 'not_submitted')) }}</span></dd>
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_email ?: $vendor->email }}</dd>
                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_phone ?: $vendor->phone ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_address ?: 'Not set' }}</dd>
                        <dt class="col-sm-4">Slug</dt>
                        <dd class="col-sm-8">{{ $vendor->shop_slug ?: 'Not set' }}</dd>
                    </dl>
                </div>
            </div>
            <div class="callout callout-info">
                <h5>Profile completeness</h5>
                <p class="mb-0">Add email, phone, address, and description so customers can understand and contact your shop.</p>
            </div>
        </div>
    </div>
@endsection
