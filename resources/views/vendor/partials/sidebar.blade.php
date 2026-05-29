<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('vendor.dashboard') }}" class="brand-link">
            <img src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('vendor/assets/img/AdminLTELogo.png') }}" alt="{{ $globalSiteInfo?->site_name ?? 'Vendor' }}" class="brand-image vendor-brand-logo opacity-75 shadow">
            <span class="brand-text fw-light">{{ auth('vendor')->user()?->shop_name ?: 'Vendor Panel' }}</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Vendor navigation" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Products <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-circle"></i><p>Product List</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-circle"></i><p>Add Product</p></a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-shop"></i>
                        <p>Shop Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
