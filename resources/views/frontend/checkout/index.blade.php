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
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @if(session('last_demo_order'))
            <div class="alert alert-info">
                Last demo payment: {{ session('last_demo_order.transaction_id') }}
            </div>
        @endif
        @if($cartItems->isEmpty())
            <div class="card"><div class="card-body text-center"><h4>Your cart is empty.</h4><a href="{{ route('home') }}" class="btn btn-normal mt-3">Continue Shopping</a></div></div>
        @else
            <div class="row">
                <div class="col-lg-7">
                    <div class="card"><div class="card-body">
                        <form method="POST" action="{{ route('checkout.place-order') }}">
                            @csrf
                            <h4>Billing Details</h4>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Full name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" placeholder="Email address" required>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Phone number" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="address" rows="4" placeholder="Delivery address" required>{{ old('address') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Payment Method</label>
                                <div class="checkout-payment-option">
                                    <label class="d-block">
                                        <input type="radio" name="payment_method" value="sslcommerz_demo" @checked(old('payment_method', 'sslcommerz_demo') === 'sslcommerz_demo')>
                                        SSLCommerz Demo Payment
                                    </label>
                                    <small class="text-muted d-block mb-2">Demo gateway only. No real money will be charged.</small>
                                    <label class="d-block">
                                        <input type="radio" name="payment_method" value="cash_on_delivery" @checked(old('payment_method') === 'cash_on_delivery')>
                                        Cash on Delivery
                                    </label>
                                </div>
                            </div>
                            <button class="btn btn-normal" type="submit">Place Order</button>
                        </form>
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
