<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Customer Dashboard'])
</head>
<body class="bg-light">
@include('frontend.partials.header')

<div class="breadcrumb-main">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-contain">
                    <div>
                        <h2>dashboard</h2>
                        <ul>
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li><i class="fa fa-angle-double-right"></i></li>
                            <li><a href="{{ route('dashboard') }}">dashboard</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-big-py-space bg-light">
    <div class="container">
        @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li class="active"><a href="{{ route('dashboard') }}">Account Info</a></li>
                            <li><a href="#">Address Book</a></li>
                            <li><a href="#">My Orders</a></li>
                            <li><a href="#">My Wishlist</a></li>
                            <li><a href="{{ route('messages.index') }}">Messages @if(($globalUserUnreadMessages ?? collect())->count() > 0)({{ ($globalUserUnreadMessages ?? collect())->count() }})@endif</a></li>
                            <li><a href="#">Newsletter</a></li>
                            <li><a href="#">Change Password</a></li>
                            <li class="last">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="border:0;background:transparent;padding:0;color:inherit;cursor:pointer">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title"><h2>My Dashboard</h2></div>
                        <div class="welcome-msg">
                            <p>Hello, {{ strtoupper(auth()->user()->name) }} !</p>
                            <p>From your My Account Dashboard you can view recent account activity, messages, and account information.</p>
                        </div>
                        <div class="box-account box-info">
                            <div class="box-head"><h2>Account Information</h2></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title"><h3>Contact Information</h3><a href="#">Edit</a></div>
                                        <div class="box-content">
                                            <h6>{{ auth()->user()->name }}</h6>
                                            <h6>{{ auth()->user()->email }}</h6>
                                            <h6>{{ auth()->user()->phone ?: 'Phone not set' }}</h6>
                                            <h6><a href="#">Change Password</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title"><h3>Messages</h3><a href="{{ route('messages.index') }}">View</a></div>
                                        <div class="box-content">
                                            <p>You have {{ ($globalUserUnreadMessages ?? collect())->count() }} unread vendor message(s).</p>
                                            <a href="{{ route('messages.index') }}" class="btn btn-normal btn-sm">Open Messages</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="box">
                                    <div class="box-title"><h3>Address Book</h3><a href="#">Manage Addresses</a></div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h6>Default Billing Address</h6>
                                            <address>You have not set a default billing address.<br><a href="#">Edit Address</a></address>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6>Default Shipping Address</h6>
                                            <address>You have not set a default shipping address.<br><a href="#">Edit Address</a></address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>
