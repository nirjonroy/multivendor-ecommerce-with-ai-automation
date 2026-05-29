@extends('backend.layouts.app')

@section('title', ($item ? 'Edit ' : 'Create ') . $config['singular'])
@section('page_title', ($item ? 'Edit ' : 'Create ') . $config['singular'])

@section('page_actions')
    <a class="btn btn-outline-primary" href="{{ route('admin.product-options.index', $resource) }}">Back</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $item ? route('admin.product-options.update', [$resource, $item->id]) : route('admin.product-options.store', $resource) }}">
                @csrf
                @if($item)
                    @method('PUT')
                @endif
                <div class="form-group row">
                    <label class="col-md-3">Name</label>
                    <div class="col-md-8"><input class="form-control" name="name" value="{{ old('name', $item?->name) }}" required></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Slug</label>
                    <div class="col-md-8"><input class="form-control" name="slug" value="{{ old('slug', $item?->slug) }}" placeholder="Auto generated if empty"></div>
                </div>
                @if($resource === 'colors')
                    <div class="form-group row">
                        <label class="col-md-3">Hex Code</label>
                        <div class="col-md-8"><input class="form-control" name="hex_code" value="{{ old('hex_code', $item?->hex_code) }}" placeholder="#000000"></div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-md-3">Owner Type</label>
                    <div class="col-md-8">
                        <select class="form-control" name="owner_type">
                            <option value="admin" @selected(old('owner_type', $item?->owner_type ?? 'admin') === 'admin')>Admin</option>
                            <option value="vendor" @selected(old('owner_type', $item?->owner_type) === 'vendor')>Vendor</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Vendor</label>
                    <div class="col-md-8">
                        <select class="form-control" name="vendor_id">
                            <option value="">No Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected(old('vendor_id', $item?->vendor_id) == $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Sort Order</label>
                    <div class="col-md-8"><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Status</label>
                    <label class="col-md-8"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item?->is_active ?? true))> Active</label>
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
