<div class="page-main-header">
    <div class="main-header-left">
        <div class="logo-wrapper">
            <a href="{{ route('admin.dashboard') }}">
                <img class="blur-up lazyloaded" src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" alt="{{ $globalSiteInfo?->site_name ?? 'Bigdeal' }}">
            </a>
        </div>
    </div>
    <div class="main-header-right row">
        <div class="mobile-sidebar">
            <div class="media-body text-right switch-sm">
                <label class="switch">
                    <input id="sidebar-toggle" type="checkbox" checked="checked"><span class="switch-state"></span>
                </label>
            </div>
        </div>
        <div class="nav-right col">
            <ul class="nav-menus">
                <li>
                    <form class="form-inline search-form">
                        <div class="form-group">
                            <input class="form-control-plaintext" type="search" placeholder="Search.."><span class="d-sm-none mobile-search"><i data-feather="search"></i></span>
                        </div>
                    </form>
                </li>
                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                <li class="onhover-dropdown"><i data-feather="bell"></i><span class="badge badge-pill badge-primary pull-right notification-badge">3</span><span class="dot"></span></li>
                <li class="onhover-dropdown">
                    <div class="media align-items-center"><img class="align-self-center pull-right img-50 rounded-circle blur-up lazyloaded" src="/assets/images/dashboard/man.png" alt="header-user"><div class="dotted-animation"><span class="animate-circle"></span><span class="main-circle"></span></div></div>
                    <ul class="profile-dropdown onhover-show-div p-20 profile-dropdown-hover">
                        <li><a href="{{ route('home') }}" target="_blank">Visit Website<span class="pull-right"><i data-feather="external-link"></i></span></a></li>
                        <li><a href="{{ route('admin.site-info.edit') }}">Settings<span class="pull-right"><i data-feather="settings"></i></span></a></li>
                        <li><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="btn btn-link p-0 text-left w-100">Logout<span class="pull-right"><i data-feather="log-out"></i></span></button></form></li>
                    </ul>
                </li>
            </ul>
            <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
        </div>
    </div>
</div>
