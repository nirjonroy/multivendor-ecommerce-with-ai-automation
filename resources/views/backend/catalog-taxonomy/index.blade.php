<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config['title'] }} - Admin</title>
    <link rel="stylesheet" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>.main-header-left .logo-wrapper img{max-width:155px;max-height:58px;object-fit:contain}.table-img{width:48px;height:48px;object-fit:contain;border:1px solid #eee}</style>
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
                        <div class="col-lg-6"><div class="page-header-left"><h3>{{ $config['title'] }}<small>Bigdeal Admin Panel</small></h3></div></div>
                        <div class="col-lg-6 text-right"><a href="{{ route('admin.catalog.create', $resource) }}" class="btn btn-primary">Create {{ $config['singular'] }}</a></div>
                    </div>
                </div>
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordernone">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                @if ($resource !== 'categories' && $resource !== 'brands')<th>Parent</th>@endif
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Sort</th>
                                <th class="text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>
                                        @php($path = $item->{$config['image_field']})
                                        @if ($path)<img class="table-img" src="{{ asset('storage/' . $path) }}" alt="">@endif
                                    </td>
                                    <td>{{ $item->name }}<br><small>{{ $item->slug }}</small></td>
                                    @if ($resource !== 'categories' && $resource !== 'brands')
                                        <td>
                                            {{ $item->category?->name }}
                                            @if ($resource === 'child-categories') / {{ $item->subCategory?->name }} @endif
                                        </td>
                                    @endif
                                    <td>{{ ucfirst($item->owner_type) }} @if($item->vendor) / {{ $item->vendor->name }} @endif</td>
                                    <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $item->sort_order }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.catalog.edit', [$resource, $item->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('admin.catalog.destroy', [$resource, $item->id]) }}" class="d-inline" onsubmit="return confirm('Delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No records found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{ $items->links() }}
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
