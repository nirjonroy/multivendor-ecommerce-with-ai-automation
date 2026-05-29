<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $globalSiteInfo?->short_description ?? 'Bigdeal Admin Panel' }}">
    <link rel="icon" href="{{ $globalSiteInfo?->favicon_path ? asset('storage/' . $globalSiteInfo->favicon_path) : asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $globalSiteInfo?->favicon_path ? asset('storage/' . $globalSiteInfo->favicon_path) : asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/flag-icon.css">
    <link rel="stylesheet" href="/assets/css/icofont.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        .main-header-left .logo-wrapper img{max-width:155px;max-height:58px;object-fit:contain}
        .sidebar-menu .sidebar-submenu a.active,.sidebar-menu .sidebar-header.active{color:#00baf2}
        .sidebar-user h6{text-transform:uppercase}
        .table-img{width:52px;height:52px;object-fit:contain;border:1px solid #eee;background:#fff}
        .content-card{background:#fff;padding:30px;border-radius:4px}
    </style>
    @stack('styles')
</head>
<body>
<div class="page-wrapper">
    @include('backend.partials.header')
    <div class="page-body-wrapper">
        @include('backend.partials.sidebar')
        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="page-header-left">
                                <h3>@yield('page_title', 'Dashboard')<small>Bigdeal Admin Panel</small></h3>
                            </div>
                        </div>
                        <div class="col-lg-6 text-right">
                            @yield('page_actions')
                        </div>
                    </div>
                </div>
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('backend.partials.scripts')
@stack('scripts')
</body>
</html>
