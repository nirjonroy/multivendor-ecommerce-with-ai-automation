<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Customer Dashboard'])
    <style>
        .customer-dashboard-wrap{padding:46px 0 70px}
        .customer-dashboard-wrap .container{max-width:1200px}
        .customer-shell{align-items:flex-start}
        .customer-sidebar{
            background:#fff;border:1px solid #e8edf3;border-radius:8px;overflow:hidden;
            box-shadow:0 10px 30px rgba(20,36,58,.05)
        }
        .customer-profile-card{padding:22px 20px;border-bottom:1px solid #eef2f6;background:#fbfdff}
        .customer-avatar{
            width:54px;height:54px;border-radius:50%;display:flex;align-items:center;justify-content:center;
            background:#12b8df;color:#fff;font-weight:700;font-size:20px;margin-bottom:12px
        }
        .customer-profile-card h5{font-size:16px;color:#1f2d3d;text-transform:none;margin-bottom:4px}
        .customer-profile-card p{color:#7b8794;font-size:13px;word-break:break-word}
        .dashboard-left.customer-sidebar .block-content ul{padding:10px}
        .dashboard-left.customer-sidebar .block-content ul li{display:block;margin:0}
        .dashboard-left.customer-sidebar .block-content ul li a,
        .dashboard-left.customer-sidebar .block-content ul li button{
            display:flex;align-items:center;gap:10px;width:100%;padding:12px 14px;border-radius:6px;
            color:#405163;font-size:15px;line-height:1.2;text-align:left
        }
        .dashboard-left.customer-sidebar .block-content ul li.active a,
        .dashboard-left.customer-sidebar .block-content ul li a:hover,
        .dashboard-left.customer-sidebar .block-content ul li button:hover{
            background:#eefbff;color:#00a9d6
        }
        .dashboard-left.customer-sidebar .block-content ul li i{width:17px;text-align:center}
        .customer-panel{
            background:#fff;border:1px solid #e8edf3;border-radius:8px;padding:28px;
            box-shadow:0 10px 30px rgba(20,36,58,.05)
        }
        .customer-panel .page-title h2{font-size:24px;font-weight:700;color:#1f2d3d;text-transform:none}
        .welcome-msg{margin:10px 0 22px}
        .welcome-msg p{letter-spacing:0;color:#637282}
        .customer-metrics{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:24px}
        .customer-metric{border:1px solid #e8edf3;border-radius:8px;padding:16px;background:#fbfdff}
        .customer-metric span{display:block;color:#7b8794;font-size:13px;text-transform:uppercase;font-weight:700}
        .customer-metric strong{display:block;color:#1f2d3d;font-size:26px;margin-top:6px}
        .customer-card-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
        .customer-info-card{border:1px solid #e8edf3;border-radius:8px;padding:18px;background:#fff}
        .customer-info-card .box-title{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #eef2f6;padding-bottom:10px;margin-bottom:14px}
        .customer-info-card .box-title h3{font-size:16px;color:#1f2d3d;text-transform:none}
        .customer-info-card .box-title a{font-weight:700;color:#00a9d6}
        .customer-info-card h6{font-size:15px;color:#405163;margin-bottom:8px;line-height:1.4}
        .customer-form-panel{border-top:1px solid #eef2f6;margin-top:24px;padding-top:22px}
        .customer-form-panel h3{font-size:18px;color:#1f2d3d;text-transform:none;margin-bottom:16px}
        .customer-dashboard-wrap .form-control{border-color:#d7dee8;border-radius:5px;height:42px;letter-spacing:0}
        .customer-dashboard-wrap textarea.form-control{height:auto}
        .customer-dashboard-wrap label{font-weight:700;color:#334155}
        .customer-dashboard-wrap .btn-normal{border-radius:5px;padding:14px 28px;background:#08b5df}
        .customer-table-card{border:1px solid #e8edf3;border-radius:8px;overflow:hidden;background:#fff}
        .customer-table-card .table{margin-bottom:0}
        .customer-table-card th{background:#f7fafc;color:#334155;border-top:0}
        .customer-empty{border:1px dashed #ccd6e2;border-radius:8px;padding:28px;text-align:center;background:#fbfdff}
        @media(max-width:991px){
            .customer-sidebar{margin-bottom:20px}
            .customer-metrics,.customer-card-grid{grid-template-columns:1fr}
            .customer-panel{padding:20px}
        }
    </style>
</head>
<body class="bg-light">
@include('frontend.partials.header')

@php
    $user = auth()->user();
    $activeSection = $section ?? 'account';
    $dashboardSections = [
        'account' => 'Account Info',
        'addresses' => 'Address Book',
        'orders' => 'My Orders',
        'wishlist' => 'My Wishlist',
        'messages' => 'Messages',
        'newsletter' => 'Newsletter',
        'password' => 'Change Password',
    ];
    $initials = collect(explode(' ', trim($user->name)))->filter()->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode('');
@endphp

<div class="breadcrumb-main">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-contain">
                    <div>
                        <h2>{{ $dashboardSections[$activeSection] ?? 'dashboard' }}</h2>
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

<section class="customer-dashboard-wrap bg-light">
    <div class="container">
        @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        <div class="row customer-shell">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left customer-sidebar">
                    <div class="customer-profile-card">
                        <div class="customer-avatar">{{ strtoupper($initials ?: 'U') }}</div>
                        <h5>{{ $user->name }}</h5>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li @class(['active' => $activeSection === 'account'])><a href="{{ route('dashboard.section', 'account') }}"><i class="fa fa-user"></i> Account Info</a></li>
                            <li @class(['active' => $activeSection === 'addresses'])><a href="{{ route('dashboard.section', 'addresses') }}"><i class="fa fa-map-marker"></i> Address Book</a></li>
                            <li @class(['active' => $activeSection === 'orders'])><a href="{{ route('dashboard.section', 'orders') }}"><i class="fa fa-shopping-bag"></i> My Orders</a></li>
                            <li @class(['active' => $activeSection === 'wishlist'])><a href="{{ route('dashboard.section', 'wishlist') }}"><i class="fa fa-heart-o"></i> My Wishlist</a></li>
                            <li><a href="{{ route('messages.index') }}"><i class="fa fa-comments-o"></i> Messages @if(($globalUserUnreadMessages ?? collect())->count() > 0)({{ ($globalUserUnreadMessages ?? collect())->count() }})@endif</a></li>
                            <li @class(['active' => $activeSection === 'newsletter'])><a href="{{ route('dashboard.section', 'newsletter') }}"><i class="fa fa-envelope-o"></i> Newsletter</a></li>
                            <li @class(['active' => $activeSection === 'password'])><a href="{{ route('dashboard.section', 'password') }}"><i class="fa fa-lock"></i> Change Password</a></li>
                            <li class="last">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="border:0;background:transparent;cursor:pointer"><i class="fa fa-sign-out"></i> Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard customer-panel">
                        @if($activeSection === 'account')
                            <div class="page-title"><h2>My Dashboard</h2></div>
                            <div class="welcome-msg">
                                <p>Hello, {{ strtoupper($user->name) }} !</p>
                                <p>From your My Account Dashboard you can view recent account activity, messages, and account information.</p>
                            </div>
                            <div class="customer-metrics">
                                <div class="customer-metric"><span>Total Orders</span><strong>{{ $orders->count() }}</strong></div>
                                <div class="customer-metric"><span>Wishlist Items</span><strong>{{ $wishlistProducts->count() }}</strong></div>
                                <div class="customer-metric"><span>Unread Messages</span><strong>{{ ($globalUserUnreadMessages ?? collect())->count() }}</strong></div>
                            </div>
                            <div class="box-account box-info">
                                <div class="box-head"><h2>Account Information</h2></div>
                                <div class="customer-card-grid">
                                    <div>
                                        <div class="box customer-info-card">
                                            <div class="box-title"><h3>Contact Information</h3><a href="#edit-account-form">Edit</a></div>
                                            <div class="box-content">
                                                <h6>{{ $user->name }}</h6>
                                                <h6>{{ $user->email }}</h6>
                                                <h6>{{ $user->phone ?: 'Phone not set' }}</h6>
                                                <h6><a href="{{ route('dashboard.section', 'password') }}">Change Password</a></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="box customer-info-card">
                                            <div class="box-title"><h3>Messages</h3><a href="{{ route('messages.index') }}">View</a></div>
                                            <div class="box-content">
                                                <p>You have {{ ($globalUserUnreadMessages ?? collect())->count() }} unread vendor message(s).</p>
                                                <a href="{{ route('messages.index') }}" class="btn btn-normal btn-sm">Open Messages</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="customer-form-panel" id="edit-account-form">
                                    <h3>Edit Contact Information</h3>
                                    <form method="POST" action="{{ route('dashboard.profile.update') }}" class="theme-form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 form-group"><label>Name</label><input class="form-control" name="name" value="{{ old('name', $user->name) }}" required></div>
                                            <div class="col-md-4 form-group"><label>Email</label><input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
                                            <div class="col-md-4 form-group"><label>Phone</label><input class="form-control" name="phone" value="{{ old('phone', $user->phone) }}"></div>
                                        </div>
                                        <button class="btn btn-normal" type="submit">Save Account Info</button>
                                    </form>
                                </div>
                            </div>
                        @elseif($activeSection === 'addresses')
                            <div class="page-title"><h2>Address Book</h2></div>
                            <form method="POST" action="{{ route('dashboard.addresses.update') }}" class="theme-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Default Billing Address</label>
                                        <textarea class="form-control" name="billing_address" rows="6" placeholder="Billing address">{{ old('billing_address', $user->billing_address) }}</textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Default Shipping Address</label>
                                        <textarea class="form-control" name="shipping_address" rows="6" placeholder="Shipping address">{{ old('shipping_address', $user->shipping_address) }}</textarea>
                                    </div>
                                </div>
                                <button class="btn btn-normal" type="submit">Save Addresses</button>
                            </form>
                        @elseif($activeSection === 'orders')
                            <div class="page-title"><h2>My Orders</h2></div>
                            <div class="table-responsive customer-table-card">
                                <table class="table">
                                    <thead><tr><th>Order</th><th>Date</th><th>Payment</th><th>Status</th><th>Total</th><th>Items</th></tr></thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}<br><small>{{ $order->transaction_id }}</small></td>
                                                <td>{{ $order->created_at?->format('d M Y h:i A') }}</td>
                                                <td>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}<br><small>{{ strtoupper(str_replace('_', ' ', $order->payment_status)) }}</small></td>
                                                <td>{{ ucfirst($order->status) }}</td>
                                                <td>{{ \App\Support\Currency::format($order->total, $globalSiteInfo) }}</td>
                                                <td>
                                                    @foreach($order->items as $item)
                                                        <div>{{ $item->product_name }} x {{ $item->quantity }}</div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">No orders yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @elseif($activeSection === 'wishlist')
                            <div class="page-title"><h2>My Wishlist</h2></div>
                            @if($wishlistProducts->isEmpty())
                                <div class="customer-empty">
                                    <p>Your wishlist is empty.</p>
                                    <a href="{{ route('home') }}" class="btn btn-normal">Browse Products</a>
                                </div>
                            @else
                                <div class="table-responsive customer-table-card">
                                    <table class="table">
                                        <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead>
                                        <tbody>
                                            @foreach($wishlistProducts as $wishlistProduct)
                                                <tr>
                                                    <td>
                                                        <img class="cart-img mr-2" src="{{ $wishlistProduct->thumbnail_path ? asset('storage/'.$wishlistProduct->thumbnail_path) : asset('assets/images/layout-2/product/1.jpg') }}" alt="{{ $wishlistProduct->name }}">
                                                        <a href="{{ route('products.show', $wishlistProduct) }}">{{ $wishlistProduct->name }}</a>
                                                    </td>
                                                    <td>{{ \App\Support\Currency::format($wishlistProduct->offer_price ?: $wishlistProduct->price, $globalSiteInfo) }}</td>
                                                    <td>{{ $wishlistProduct->stock_quantity > 0 ? 'In Stock' : 'Stock Out' }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('wishlist.destroy', $wishlistProduct) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" type="submit">Remove</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @elseif($activeSection === 'newsletter')
                            <div class="page-title"><h2>Newsletter</h2></div>
                            <form method="POST" action="{{ route('dashboard.newsletter.update') }}" class="theme-form">
                                @csrf
                                <label class="d-flex align-items-center mb-3">
                                    <input type="checkbox" name="newsletter_subscribed" value="1" class="mr-2" @checked($user->newsletter_subscribed)>
                                    Subscribe me to marketplace newsletter updates.
                                </label>
                                <button class="btn btn-normal" type="submit">Save Newsletter Preference</button>
                            </form>
                        @elseif($activeSection === 'password')
                            <div class="page-title"><h2>Change Password</h2></div>
                            <form method="POST" action="{{ route('dashboard.password.update') }}" class="theme-form">
                                @csrf
                                <div class="form-group"><label>Current Password</label><input class="form-control" type="password" name="current_password" required></div>
                                <div class="form-group"><label>New Password</label><input class="form-control" type="password" name="password" required></div>
                                <div class="form-group"><label>Confirm New Password</label><input class="form-control" type="password" name="password_confirmation" required></div>
                                <button class="btn btn-normal" type="submit">Change Password</button>
                            </form>
                        @endif
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
