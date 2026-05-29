<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Checkout'])
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container"><div class="breadcrumb-contain"><div><h2>Checkout</h2><ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a>checkout</a></li></ul></div></div></div>
</section>
<section class="checkout-wrap bg-light">
    <div class="custom-container">
        @if($cartItems->isEmpty())
            <div class="card"><div class="card-body text-center"><h4>Your cart is empty.</h4><a href="{{ route('home') }}" class="btn btn-normal mt-3">Continue Shopping</a></div></div>
        @else
            <div class="row">
                <div class="col-lg-7">
                    <div class="card"><div class="card-body">
                        <h4>Billing Details</h4>
                        <div class="form-group"><label>Name</label><input class="form-control" placeholder="Full name"></div>
                        <div class="form-group"><label>Email</label><input class="form-control" type="email" placeholder="Email address"></div>
                        <div class="form-group"><label>Phone</label><input class="form-control" placeholder="Phone number"></div>
                        <div class="form-group"><label>Address</label><textarea class="form-control" rows="4" placeholder="Delivery address"></textarea></div>
                        <button class="btn btn-normal" type="button">Place Order</button>
                    </div></div>
                </div>
                <div class="col-lg-5">
                    <div class="card"><div class="card-body">
                        <h4>Order Summary</h4>
                        @foreach($cartItems as $item)
                            <div class="summary-line">
                                <span>{{ $item['product']->name }} x {{ $item['quantity'] }}</span>
                                <strong>{{ \App\Support\Currency::format($item['subtotal'], $globalSiteInfo) }}</strong>
                            </div>
                        @endforeach
                        <div class="summary-line"><span>Subtotal</span><strong>{{ \App\Support\Currency::format($cartItems->sum('subtotal'), $globalSiteInfo) }}</strong></div>
                        <div class="summary-line"><span>Shipping</span><strong>{{ \App\Support\Currency::format(0, $globalSiteInfo) }}</strong></div>
                        <div class="summary-line"><span>Total</span><strong>{{ \App\Support\Currency::format($cartItems->sum('subtotal'), $globalSiteInfo) }}</strong></div>
                    </div></div>
                </div>
            </div>
        @endif
    </div>
</section>
@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
</body>
</html>
