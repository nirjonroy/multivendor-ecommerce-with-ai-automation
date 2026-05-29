@extends('backend.layouts.app')

@section('title', ($item ? 'Edit ' : 'Create ') . $config['singular'])
@section('page_title', ($item ? 'Edit ' : 'Create ') . $config['singular'])

@section('page_actions')
    <a href="{{ route('admin.catalog.index', $resource) }}" class="btn btn-outline-primary">Back</a>
@endsection

@push('styles')
    <style>.preview-img{max-height:80px;max-width:160px;object-fit:contain;border:1px solid #ddd;padding:4px;margin-top:8px}</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $item ? route('admin.catalog.update', [$resource, $item->id]) : route('admin.catalog.store', $resource) }}" enctype="multipart/form-data">
                @csrf
                @if($item)
                    @method('PUT')
                @endif

                @if ($resource === 'sub-categories' || $resource === 'child-categories')
                    <div class="form-group row">
                        <label class="col-xl-3 col-md-4">Category</label>
                        <div class="col-xl-8 col-md-7">
                            <select class="form-control" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $item?->category_id) == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($resource === 'child-categories')
                    <div class="form-group row">
                        <label class="col-xl-3 col-md-4">Sub Category</label>
                        <div class="col-xl-8 col-md-7">
                            <select class="form-control" name="sub_category_id" required>
                                <option value="">Select Sub Category</option>
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}" @selected(old('sub_category_id', $item?->sub_category_id) == $subCategory->id)>{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="col-xl-3 col-md-4"><span>*</span> Name</label>
                    <div class="col-xl-8 col-md-7"><input class="form-control" name="name" value="{{ old('name', $item?->name) }}" required></div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">Slug</label>
                    <div class="col-xl-8 col-md-7"><input class="form-control" name="slug" value="{{ old('slug', $item?->slug) }}" placeholder="Auto generated from name if empty"></div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">Description</label>
                    <div class="col-xl-8 col-md-7"><textarea class="form-control" name="description" rows="3">{{ old('description', $item?->description) }}</textarea></div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">Owner Type</label>
                    <div class="col-xl-8 col-md-7">
                        <select class="form-control" name="owner_type" required>
                            <option value="admin" @selected(old('owner_type', $item?->owner_type ?? 'admin') === 'admin')>Admin</option>
                            <option value="vendor" @selected(old('owner_type', $item?->owner_type) === 'vendor')>Vendor</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">Vendor</label>
                    <div class="col-xl-8 col-md-7">
                        <select class="form-control" name="vendor_id">
                            <option value="">No Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected(old('vendor_id', $item?->vendor_id) == $vendor->id)>{{ $vendor->name }} - {{ $vendor->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">{{ $resource === 'brands' ? 'Logo' : 'Image' }}</label>
                    <div class="col-xl-8 col-md-7">
                        <input class="form-control" type="file" name="{{ $config['upload_input'] }}" accept="image/*">
                        @if ($item && $item->{$config['image_field']})
                            <img class="preview-img" src="{{ asset('storage/' . $item->{$config['image_field']}) }}" alt="">
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">Sort Order</label>
                    <div class="col-xl-8 col-md-7"><input class="form-control" type="number" min="0" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}"></div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-md-4">Status</label>
                    <label class="col-xl-8 col-md-7"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item?->is_active ?? true))> Active</label>
                </div>
                <div class="pull-right"><button class="btn btn-primary" type="submit">Save</button></div>
            </form>
        </div>
    </div>
@endsection
