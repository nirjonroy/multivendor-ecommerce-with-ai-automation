<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Info - Bigdeal Admin</title>
    <link rel="icon" href="{{ $globalSiteInfo?->favicon_path ? asset('storage/' . $globalSiteInfo->favicon_path) : asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/admin.css">
    <style>
        .main-header-left .logo-wrapper img {
            max-width: 155px;
            max-height: 58px;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="page-main-header">
        <div class="main-header-left">
            <div class="logo-wrapper">
                <a href="{{ route('admin.dashboard') }}"><img class="blur-up lazyloaded" src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" alt="{{ $globalSiteInfo?->site_name ?? 'Bigdeal' }}"></a>
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
                    <li class="onhover-dropdown">
                        <div class="media align-items-center">
                            <img class="align-self-center pull-right img-50 rounded-circle blur-up lazyloaded" src="/assets/images/dashboard/man.png" alt="header-user">
                        </div>
                        <ul class="profile-dropdown onhover-show-div p-20 profile-dropdown-hover">
                            <li><a href="{{ route('admin.site-info.edit') }}">Settings<span class="pull-right"><i data-feather="settings"></i></span></a></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-left w-100">Logout<span class="pull-right"><i data-feather="log-out"></i></span></button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
            </div>
        </div>
    </div>

    <div class="page-body-wrapper">
        <div class="page-sidebar">
            <div class="sidebar custom-scrollbar">
                <div class="sidebar-user text-center">
                    <div><img class="img-60 rounded-circle lazyloaded blur-up" src="/assets/images/dashboard/man.png" alt=""></div>
                    <h6 class="mt-3 f-14">{{ auth('admin')->user()->name }}</h6>
                    <p>{{ auth('admin')->user()->is_super_admin ? 'Super Admin' : 'Admin' }}</p>
                </div>
                <ul class="sidebar-menu">
                    <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a></li>
                    <li>
                        <a class="sidebar-header" href="#"><i data-feather="box"></i><span>Catalog</span><i class="fa fa-angle-right pull-right"></i></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.catalog.index', 'categories') }}"><i class="fa fa-circle"></i>Categories</a></li>
                            <li><a href="{{ route('admin.catalog.index', 'sub-categories') }}"><i class="fa fa-circle"></i>Sub Categories</a></li>
                            <li><a href="{{ route('admin.catalog.index', 'child-categories') }}"><i class="fa fa-circle"></i>Child Categories</a></li>
                            <li><a href="{{ route('admin.catalog.index', 'brands') }}"><i class="fa fa-circle"></i>Brands</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="sidebar-header active" href="#"><i data-feather="settings"></i><span>Settings</span><i class="fa fa-angle-right pull-right"></i></a>
                        <ul class="sidebar-submenu menu-open" style="display:block;">
                            <li><a class="active" href="{{ route('admin.site-info.edit') }}"><i class="fa fa-circle"></i>Site Info</a></li>
                            <li><a href="{{ route('admin.home-section.edit') }}"><i class="fa fa-circle"></i>Home Section</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="page-header-left">
                                <h3>Site Info
                                    <small>Bigdeal Admin Panel</small>
                                </h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ol class="breadcrumb pull-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item">Settings</li>
                                <li class="breadcrumb-item active">Site Info</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <div class="card tab2-card">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-material" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><i data-feather="globe" class="mr-2"></i>General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#social" role="tab"><i data-feather="share-2" class="mr-2"></i>Social</a>
                                    </li>
                                </ul>

                                <form class="needs-validation user-add" method="POST" action="{{ route('admin.site-info.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                                            <h5 class="f-w-600">Website Information</h5>
                                            <div class="form-group row">
                                                <label for="site_name" class="col-xl-3 col-md-4"><span>*</span> Site Name</label>
                                                <input class="form-control col-xl-8 col-md-7" id="site_name" name="site_name" type="text" value="{{ old('site_name', $siteInfo->site_name) }}" required>
                                            </div>
                                            <div class="form-group row">
                                                <label for="site_url" class="col-xl-3 col-md-4">Site URL</label>
                                                <input class="form-control col-xl-8 col-md-7" id="site_url" name="site_url" type="url" value="{{ old('site_url', $siteInfo->site_url) }}" placeholder="https://example.com">
                                            </div>
                                            <div class="form-group row">
                                                <label for="contact_email" class="col-xl-3 col-md-4">Contact Email</label>
                                                <input class="form-control col-xl-8 col-md-7" id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $siteInfo->contact_email) }}">
                                            </div>
                                            <div class="form-group row">
                                                <label for="contact_phone" class="col-xl-3 col-md-4">Contact Phone</label>
                                                <input class="form-control col-xl-8 col-md-7" id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $siteInfo->contact_phone) }}">
                                            </div>
                                            <div class="form-group row">
                                                <label for="address" class="col-xl-3 col-md-4">Address</label>
                                                <textarea class="form-control col-xl-8 col-md-7" id="address" name="address" rows="3">{{ old('address', $siteInfo->address) }}</textarea>
                                            </div>
                                            <div class="form-group row">
                                                <label for="short_description" class="col-xl-3 col-md-4">Short Description</label>
                                                <textarea class="form-control col-xl-8 col-md-7" id="short_description" name="short_description" rows="4">{{ old('short_description', $siteInfo->short_description) }}</textarea>
                                            </div>
                                            <div class="form-group row">
                                                <label for="logo" class="col-xl-3 col-md-4">Logo</label>
                                                <div class="col-xl-8 col-md-7 p-0">
                                                    <input class="form-control" id="logo" name="logo" type="file" accept="image/*">
                                                    @if ($siteInfo->logo_path)
                                                        <img src="{{ asset('storage/' . $siteInfo->logo_path) }}" class="mt-3" style="max-height:70px;" alt="Current logo">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="favicon" class="col-xl-3 col-md-4">Favicon</label>
                                                <div class="col-xl-8 col-md-7 p-0">
                                                    <input class="form-control" id="favicon" name="favicon" type="file" accept=".ico,image/*">
                                                    @if ($siteInfo->favicon_path)
                                                        <img src="{{ asset('storage/' . $siteInfo->favicon_path) }}" class="mt-3" style="max-height:40px;" alt="Current favicon">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="social" role="tabpanel">
                                            <h5 class="f-w-600">Social Links</h5>
                                            <div class="form-group row">
                                                <label for="facebook_url" class="col-xl-3 col-md-4">Facebook URL</label>
                                                <input class="form-control col-xl-8 col-md-7" id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url', $siteInfo->facebook_url) }}">
                                            </div>
                                            <div class="form-group row">
                                                <label for="instagram_url" class="col-xl-3 col-md-4">Instagram URL</label>
                                                <input class="form-control col-xl-8 col-md-7" id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $siteInfo->instagram_url) }}">
                                            </div>
                                            <div class="form-group row">
                                                <label for="youtube_url" class="col-xl-3 col-md-4">YouTube URL</label>
                                                <input class="form-control col-xl-8 col-md-7" id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url', $siteInfo->youtube_url) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 footer-copyright">
                        <p class="mb-0">Copyright {{ date('Y') }} © Bigdeal All rights reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <p class="pull-right mb-0">Hand crafted & made with<i class="fa fa-heart"></i></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="/assets/js/icons/feather-icon/feather-icon.js"></script>
<script src="/assets/js/sidebar-menu.js"></script>
<script src="/assets/js/admin-customizer.js"></script>
<script src="/assets/js/lazysizes.min.js"></script>
<script src="/assets/js/admin-script.js"></script>
</body>
</html>
