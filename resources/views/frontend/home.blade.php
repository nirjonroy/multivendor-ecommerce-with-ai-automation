<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bigdeal Marketplace</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color2.css') }}">
</head>
<body>
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>Bigdeal Marketplace</h1>
                <p class="lead">Customer, vendor, and admin authentication is connected to Laravel guards.</p>
                <a href="{{ route('login') }}" class="btn btn-normal">Customer / Vendor Login</a>
                <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary ml-2">Admin Login</a>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <img src="{{ asset('assets/images/layout-2/logo/logo.png') }}" class="img-fluid" alt="Bigdeal">
            </div>
        </div>
    </div>
</section>
</body>
</html>
