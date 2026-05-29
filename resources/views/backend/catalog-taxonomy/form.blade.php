<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item ? 'Edit' : 'Create' }} {{ $config['singular'] }}</title>
    <link rel="stylesheet" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>.main-header-left .logo-wrapper img{max-width:155px;max-height:58px;object-fit:contain}.preview-img{max-height:80px;max-width:160px;object-fit:contain;border:1px solid #ddd;padding:4px;margin-top:8px}</style>
</head>
<body>
<div class="page-wrapper">
    <div class="page-main-header">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" alt=""></a></div>
        </div>
    </div>
    <div class="page-body-wrapper">
        <div class="page-sidebar">
            <div class="sidebar custom-scrollbar">
                <div class="sidebar-user text-center">
                    <img class="img-60 rounded-circle" src="/assets/images/dashboard/man.png" alt="">
                    <h6 class="mt-3 f-14">{{ auth('admin')->user()->name }}</h6>
                    <p>{{ auth('admin')->user()->is_super_admin ? 'Super Admin' : 'Admin' }}</p>
                </div>
                <ul class="sidebar-menu">
                    <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
                    <li><a class="sidebar-header" href="{{ route('admin.catalog.index', 'categories') }}"><i class="fa fa-list"></i><span>Categories</span></a></li>
                    <li><a class="sidebar-header" href="{{ route('admin.catalog.index', 'sub-categories') }}"><i class="fa fa-sitemap"></i><span>Sub Categories</span></a></li>
                    <li><a class="sidebar-header" href="{{ route('admin.catalog.index', 'child-categories') }}"><i class="fa fa-indent"></i><span>Child Categories</span></a></li>
                    <li><a class="sidebar-header" href="{{ route('admin.catalog.index', 'brands') }}"><i class="fa fa-tags"></i><span>Brands</span></a></li>
                </ul>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6"><div class="page-header-left"><h3>{{ $item ? 'Edit' : 'Create' }} {{ $config['singular'] }}<small>Bigdeal Admin Panel</small></h3></div></div>
                        <div class="col-lg-6 text-right"><a href="{{ route('admin.catalog.index', $resource) }}" class="btn btn-outline-primary">Back</a></div>
                    </div>
                </div>
                @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ $item ? route('admin.catalog.update', [$resource, $item->id]) : route('admin.catalog.store', $resource) }}" enctype="multipart/form-data">
                            @csrf
                            @if($item) @method('PUT') @endif

                            @if ($resource === 'sub-categories' || $resource === 'child-categories')
                                <div class="form-group row">
                                    <label class="col-xl-3 col-md-4">Category</label>
                                    <select class="form-control col-xl-8 col-md-7" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id', $item?->category_id) == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($resource === 'child-categories')
                                <div class="form-group row">
                                    <label class="col-xl-3 col-md-4">Sub Category</label>
                                    <select class="form-control col-xl-8 col-md-7" name="sub_category_id" required>
                                        <option value="">Select Sub Category</option>
                                        @foreach ($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" @selected(old('sub_category_id', $item?->sub_category_id) == $subCategory->id)>{{ $subCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4"><span>*</span> Name</label>
                                <input class="form-control col-xl-8 col-md-7" name="name" value="{{ old('name', $item?->name) }}" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">Slug</label>
                                <input class="form-control col-xl-8 col-md-7" name="slug" value="{{ old('slug', $item?->slug) }}" placeholder="Auto generated from name if empty">
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">Description</label>
                                <textarea class="form-control col-xl-8 col-md-7" name="description" rows="3">{{ old('description', $item?->description) }}</textarea>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">Owner Type</label>
                                <select class="form-control col-xl-8 col-md-7" name="owner_type" required>
                                    <option value="admin" @selected(old('owner_type', $item?->owner_type ?? 'admin') === 'admin')>Admin</option>
                                    <option value="vendor" @selected(old('owner_type', $item?->owner_type) === 'vendor')>Vendor</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">Vendor</label>
                                <select class="form-control col-xl-8 col-md-7" name="vendor_id">
                                    <option value="">No Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" @selected(old('vendor_id', $item?->vendor_id) == $vendor->id)>{{ $vendor->name }} - {{ $vendor->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">{{ $resource === 'brands' ? 'Logo' : 'Image' }}</label>
                                <div class="col-xl-8 col-md-7 p-0">
                                    <input class="form-control" type="file" name="{{ $config['upload_input'] }}" accept="image/*">
                                    @if ($item && $item->{$config['image_field']})
                                        <img class="preview-img" src="{{ asset('storage/' . $item->{$config['image_field']}) }}" alt="">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">Sort Order</label>
                                <input class="form-control col-xl-8 col-md-7" type="number" min="0" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}">
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-md-4">Status</label>
                                <label class="col-xl-8 col-md-7 p-0">
                                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item?->is_active ?? true))> Active
                                </label>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
</body>
</html>
