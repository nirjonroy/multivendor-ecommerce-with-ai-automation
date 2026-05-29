<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Shopping Cart'])
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container"><div class="breadcrumb-contain"><div><h2>Shopping Cart</h2><ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a>cart</a></li></ul></div></div></div>
</section>
<section class="cart-wrap bg-light">
    <div class="custom-container">
        @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
        @if($cartItems->isEmpty())
            <div class="card"><div class="card-body text-center"><h4>Your cart is empty.</h4><a href="{{ route('home') }}" class="btn btn-normal mt-3">Continue Shopping</a></div></div>
        @else
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Product</th><th>Options</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr data-cart-row="{{ $item['product']->id }}">
                                    <td><img class="cart-img mr-2" src="{{ $item['product']->thumbnail_path ? asset('storage/'.$item['product']->thumbnail_path) : asset('assets/images/layout-2/product/1.jpg') }}" alt="{{ $item['product']->name }}"> {{ $item['product']->name }}</td>
                                    <td>Size: {{ $item['size'] ?: 'N/A' }}<br>Color: {{ $item['color'] ?: 'N/A' }}</td>
                                    <td>{{ \App\Support\Currency::format($item['price'], $globalSiteInfo) }}</td>
                                    <td>
                                        <form class="cart-quantity-form" method="POST" action="{{ route('cart.update', $item['product']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input class="form-control d-inline-block cart-quantity-input" style="width:90px" type="number" name="quantity" min="1" max="{{ $item['product']->stock_quantity }}" value="{{ $item['quantity'] }}" autocomplete="off">
                                        </form>
                                    </td>
                                    <td class="cart-row-subtotal">{{ \App\Support\Currency::format($item['subtotal'], $globalSiteInfo) }}</td>
                                    <td class="cart-actions">
                                        <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        <h4>Total: <span id="cart-total">{{ \App\Support\Currency::format($cartItems->sum('subtotal'), $globalSiteInfo) }}</span></h4>
                        <a href="{{ route('checkout.index') }}" class="btn btn-normal">Checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script>
    const cartUpdateTimers = {};

    function updateCartQuantity(form) {
        const input = form.querySelector('.cart-quantity-input');
        const row = form.closest('[data-cart-row]');
        const token = form.querySelector('[name="_token"]').value;
        const method = form.querySelector('[name="_method"]').value;

        input.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _method: method,
                quantity: input.value
            })
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Unable to update cart.');
                }
                return response.json();
            })
            .then(function (data) {
                row.querySelector('.cart-row-subtotal').textContent = data.subtotal;
                document.getElementById('cart-total').textContent = data.total;
                document.querySelectorAll('.cart-product').forEach(function (cartCount) {
                    cartCount.textContent = data.cart_quantity;
                });
            })
            .catch(function () {
                form.submit();
            })
            .finally(function () {
                input.disabled = false;
                input.focus();
            });
    }

    document.querySelectorAll('.cart-quantity-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const form = input.closest('form');
            const min = Number(input.min || 1);
            const max = Number(input.max || 999999);
            let value = Number(input.value || min);
            value = Math.max(min, Math.min(max, value));
            input.value = value;

            clearTimeout(cartUpdateTimers[form.action]);
            cartUpdateTimers[form.action] = setTimeout(function () {
                updateCartQuantity(form);
            }, 350);
        });

        input.addEventListener('change', function () {
            clearTimeout(cartUpdateTimers[input.closest('form').action]);
            updateCartQuantity(input.closest('form'));
        });
    });
</script>
</body>
</html>
