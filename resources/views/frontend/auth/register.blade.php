<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Register - Bigdeal'])
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>register</h2>
                <ul>
                    <li><a href="{{ route('home') }}">home</a></li>
                    <li><i class="fa fa-angle-double-right"></i></li>
                    <li><a href="{{ route('register') }}">register</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="register-page section-big-py-space bg-light">
    <div class="custom-container">
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-8 offset-xl-4 offset-lg-3 offset-md-2">
                <div class="theme-card">
                    <h3 class="text-center">Create account</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form class="theme-form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="account_type">Account type</label>
                            <select class="form-control" id="account_type" name="account_type" required>
                                <option value="user" @selected(old('account_type') === 'user')>Customer</option>
                                <option value="vendor" @selected(old('account_type') === 'vendor')>Vendor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="shop_name">Shop Name</label>
                            <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ old('shop_name') }}" placeholder="Required for vendors">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                        </div>
                        <button type="submit" class="btn btn-normal">Create Account</button>
                    </form>
                    <a href="{{ route('login') }}" class="txt-default pt-3 d-block">Already have an account?</a>
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')
<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
