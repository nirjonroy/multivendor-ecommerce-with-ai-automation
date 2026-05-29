@php
    $admin = auth('admin')->user();
    $routeName = request()->route()?->getName();
    $catalogResource = request()->route('resource');
    $productOpen = request()->routeIs('admin.products.*')
        || request()->routeIs('admin.product-options.*')
        || (request()->routeIs('admin.catalog.*') && in_array($catalogResource, ['categories', 'sub-categories', 'child-categories', 'brands'], true));
    $vendorsOpen = request()->routeIs('admin.vendors.*');
    $settingsOpen = request()->routeIs('admin.site-info.*') || request()->routeIs('admin.home-section.*');
@endphp
<div class="page-sidebar">
    <div class="sidebar custom-scrollbar">
        <div class="sidebar-user text-center">
            <div><img class="img-60 rounded-circle lazyloaded blur-up" src="/assets/images/dashboard/man.png" alt="admin"></div>
            <h6 class="mt-3 f-14">{{ $admin?->name ?? 'Super Admin' }}</h6>
            <p>{{ strtoupper($admin?->role ?? 'Admin') }}</p>
        </div>
        <ul class="sidebar-menu">
            <li><a class="sidebar-header {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a></li>
            <li>
                <a class="sidebar-header {{ $productOpen ? 'active' : '' }}" href="#"><i data-feather="box"></i><span>Products</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="sidebar-submenu" style="{{ $productOpen ? 'display:block;' : '' }}">
                    <li><a class="{{ $routeName === 'admin.products.index' ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="fa fa-circle"></i>Product List</a></li>
                    <li><a class="{{ $routeName === 'admin.products.create' ? 'active' : '' }}" href="{{ route('admin.products.create') }}"><i class="fa fa-circle"></i>Add Product</a></li>
                    <li><a class="{{ $catalogResource === 'categories' ? 'active' : '' }}" href="{{ route('admin.catalog.index', 'categories') }}"><i class="fa fa-circle"></i>Categories</a></li>
                    <li><a class="{{ $catalogResource === 'sub-categories' ? 'active' : '' }}" href="{{ route('admin.catalog.index', 'sub-categories') }}"><i class="fa fa-circle"></i>Sub Categories</a></li>
                    <li><a class="{{ $catalogResource === 'child-categories' ? 'active' : '' }}" href="{{ route('admin.catalog.index', 'child-categories') }}"><i class="fa fa-circle"></i>Child Categories</a></li>
                    <li><a class="{{ $catalogResource === 'brands' ? 'active' : '' }}" href="{{ route('admin.catalog.index', 'brands') }}"><i class="fa fa-circle"></i>Brands</a></li>
                    <li><a class="{{ request()->is('admin/product-options/sizes*') ? 'active' : '' }}" href="{{ route('admin.product-options.index', 'sizes') }}"><i class="fa fa-circle"></i>Size</a></li>
                    <li><a class="{{ request()->is('admin/product-options/colors*') ? 'active' : '' }}" href="{{ route('admin.product-options.index', 'colors') }}"><i class="fa fa-circle"></i>Color</a></li>
                </ul>
            </li>
            <li><a class="sidebar-header {{ $vendorsOpen ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}"><i data-feather="users"></i><span>Vendors</span></a></li>
            <li>
                <a class="sidebar-header {{ $settingsOpen ? 'active' : '' }}" href="#"><i data-feather="settings"></i><span>Settings</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="sidebar-submenu" style="{{ $settingsOpen ? 'display:block;' : '' }}">
                    <li><a class="{{ request()->routeIs('admin.site-info.*') ? 'active' : '' }}" href="{{ route('admin.site-info.edit') }}"><i class="fa fa-circle"></i>Site Info</a></li>
                    <li><a class="{{ request()->routeIs('admin.home-section.*') ? 'active' : '' }}" href="{{ route('admin.home-section.edit') }}"><i class="fa fa-circle"></i>Home Section</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
