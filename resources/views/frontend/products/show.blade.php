<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => $product->name])
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>{{ $product->name }}</h2>
                <ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a>{{ $product->name }}</a></li></ul>
            </div>
        </div>
    </div>
</section>
<section class="section-big-pt-space bg-light">
    <div class="collection-wrapper">
        <div class="custom-container">
            <div class="row">
                <div class="col-lg-1 col-sm-2 col-xs-12">
                    <div class="row">
                        @foreach (($product->gallery_paths ?: []) as $path)
                            <div class="col-12 p-1"><img src="{{ asset('storage/'.$path) }}" class="img-fluid" alt="{{ $product->name }}"></div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5 col-sm-10 col-xs-12">
                    <div class="product-slick">
                        <div><img src="{{ $product->thumbnail_path ? asset('storage/'.$product->thumbnail_path) : asset('assets/images/layout-2/product/1.jpg') }}" class="img-fluid image_zoom_cls-0" alt="{{ $product->name }}"></div>
                    </div>
                </div>
                <div class="col-lg-6 rtl-text">
                    <div class="product-right">
                        <h2>{{ $product->name }}</h2>
                        <h4>
                            @if($product->offer_price)<del>{{ \App\Support\Currency::format($product->price, $globalSiteInfo) }}</del>@endif
                            <span>{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</span>
                        </h4>
                        <div class="border-product">
                            <h6 class="product-title">Product Details</h6>
                            <div>{!! $product->long_description ?: nl2br(e($product->short_description)) !!}</div>
                        </div>
                        <div class="product-description border-product">
                            <h6 class="product-title">SKU: {{ $product->sku ?: 'N/A' }}</h6>
                            <h6 class="product-title">Category: {{ $product->category?->name }}</h6>
                            <h6 class="product-title">Brand: {{ $product->brand?->name ?: 'N/A' }}</h6>
                            <h6 class="product-title">Stock: {{ $product->stock_quantity > 0 ? $product->stock_quantity : 'Stock Out Product' }}</h6>
                        </div>
                        <div class="product-buttons">
                            @if($product->stock_quantity > 0)
                                <div class="cart-option-grid">
                                    @if(!empty($product->sizes))
                                        <select class="form-control product-size-selector">
                                            @foreach($product->sizes as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @if(!empty($product->colors))
                                        <select class="form-control product-color-selector">
                                            @foreach($product->colors as $color)
                                                <option value="{{ $color }}">{{ $color }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <input class="form-control product-quantity-selector" type="number" min="1" max="{{ $product->stock_quantity }}" value="1">
                                </div>
                                <form method="POST" action="{{ route('cart.store', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="size" value="{{ $product->sizes[0] ?? '' }}">
                                    <input type="hidden" name="color" value="{{ $product->colors[0] ?? '' }}">
                                    <button type="submit" class="btn btn-normal">add to cart</button>
                                </form>
                                <form method="POST" action="{{ route('cart.buy-now', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="size" value="{{ $product->sizes[0] ?? '' }}">
                                    <input type="hidden" name="color" value="{{ $product->colors[0] ?? '' }}">
                                    <button type="submit" class="btn btn-normal">buy now</button>
                                </form>
                            @else
                                <span class="btn btn-normal disabled">Stock Out Product</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/slick.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/script.js"></script>
<script>
    function syncProductForms() {
        const quantity = document.querySelector('.product-quantity-selector')?.value || 1;
        const size = document.querySelector('.product-size-selector')?.value || '';
        const color = document.querySelector('.product-color-selector')?.value || '';
        document.querySelectorAll('.product-buttons form').forEach(function (form) {
            const quantityInput = form.querySelector('[name="quantity"]');
            const sizeInput = form.querySelector('[name="size"]');
            const colorInput = form.querySelector('[name="color"]');
            if (quantityInput) quantityInput.value = quantity;
            if (sizeInput) sizeInput.value = size;
            if (colorInput) colorInput.value = color;
        });
    }
    document.querySelectorAll('.product-size-selector,.product-color-selector,.product-quantity-selector').forEach(function (input) {
        input.addEventListener('change', syncProductForms);
        input.addEventListener('input', syncProductForms);
    });
</script>
</body>
</html>
