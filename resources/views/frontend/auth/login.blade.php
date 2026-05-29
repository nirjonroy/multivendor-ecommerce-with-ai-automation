<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Bigdeal</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color2.css') }}" id="color">
</head>
<body>
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>login</h2>
                <ul>
                    <li><a href="{{ route('home') }}">home</a></li>
                    <li><i class="fa fa-angle-double-right"></i></li>
                    <li><a href="{{ route('login') }}">login</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="login-page section-big-py-space bg-light">
    <div class="custom-container">
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-8 offset-xl-4 offset-lg-3 offset-md-2">
                <div class="theme-card">
                    <h3 class="text-center">Login</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form class="theme-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="account_type">Account type</label>
                            <select class="form-control" id="account_type" name="account_type" required>
                                <option value="user" @selected(old('account_type') === 'user')>Customer</option>
                                <option value="vendor" @selected(old('account_type') === 'vendor')>Vendor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="form-group">
                            <label class="d-inline-flex align-items-center">
                                <input type="checkbox" name="remember" value="1" class="mr-2"> Remember me
                            </label>
                        </div>
                        <button type="submit" class="btn btn-normal">Login</button>
                    </form>
                    <p class="mt-3">Create a customer account or apply as a vendor to start selling.</p>
                    <a href="{{ route('register') }}" class="txt-default pt-3 d-block">Create an Account</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
