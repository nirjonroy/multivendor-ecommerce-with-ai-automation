<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bigdeal</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
@php($registerActive = request()->routeIs('admin.register'))
<div class="page-wrapper">
    <div class="authentication-box">
        <div class="container">
            <div class="row">
                <div class="col-md-5 p-0 card-left">
                    <div class="card bg-primary">
                        <div class="single-item">
                            <div>
                                <div>
                                    <h3>Welcome to Bigdeal</h3>
                                    <p>Manage vendors, products, orders, customers, and marketplace operations from one admin workspace.</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <h3>Multi-vendor Control</h3>
                                    <p>Approve sellers, track sales, and keep the ecommerce workflow organized.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 p-0 card-right">
                    <div class="card tab2-card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $registerActive ? '' : 'active' }}" href="{{ route('admin.login') }}"><span class="icon-user mr-2"></span>Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $registerActive ? 'active' : '' }}" href="{{ route('admin.register') }}"><span class="icon-unlock mr-2"></span>Register</a>
                                </li>
                            </ul>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-4 mb-0">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="tab-content">
                                @if (! $registerActive)
                                    <div class="tab-pane fade show active">
                                        <form class="form-horizontal auth-form" method="POST" action="{{ route('admin.login') }}">
                                            @csrf
                                            <div class="form-group">
                                                <input required name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Email" autofocus>
                                            </div>
                                            <div class="form-group">
                                                <input required name="password" type="password" class="form-control" placeholder="Password">
                                            </div>
                                            <div class="form-terms">
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="1">
                                                    <label class="custom-control-label" for="remember">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="form-button">
                                                <button class="btn btn-primary" type="submit">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="tab-pane fade show active">
                                        <form class="form-horizontal auth-form" method="POST" action="{{ route('admin.register') }}">
                                            @csrf
                                            <div class="form-group">
                                                <input required name="name" type="text" class="form-control" value="{{ old('name') }}" placeholder="Name" autofocus>
                                            </div>
                                            <div class="form-group">
                                                <input required name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input name="phone" type="text" class="form-control" value="{{ old('phone') }}" placeholder="Phone">
                                            </div>
                                            <div class="form-group">
                                                <input required name="password" type="password" class="form-control" placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <input required name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
                                            </div>
                                            <div class="form-button">
                                                <button class="btn btn-primary" type="submit">Register</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/admin-script.js') }}"></script>
</body>
</html>
