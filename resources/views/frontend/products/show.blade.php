<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => $product->name])
    <style>
        .product-thumb{width:100%;height:88px;object-fit:cover;border:2px solid transparent;cursor:pointer;margin-bottom:8px}
        .product-thumb.active{border-color:#00baf2}
        .product-main-image-wrap{overflow:hidden;border:1px solid #eee;background:#fff}
        .product-main-image{width:100%;transition:transform .25s ease;cursor:zoom-in}
        .product-main-image-wrap:hover .product-main-image{transform:scale(1.6)}
        .vendor-contact-card{border:1px solid #e5e5e5;padding:18px;background:#fff;margin-top:18px}
    </style>
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
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <div class="row">
                <div class="col-lg-1 col-sm-2 col-xs-12">
                    <div class="row">
                        @php($productImages = collect([$product->thumbnail_path])->merge($product->gallery_paths ?: [])->filter()->values())
                        @foreach ($productImages as $index => $path)
                            <div class="col-12 p-1">
                                <img src="{{ asset('storage/'.$path) }}" class="img-fluid product-thumb {{ $index === 0 ? 'active' : '' }}" data-full-image="{{ asset('storage/'.$path) }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5 col-sm-10 col-xs-12">
                    <div class="product-main-image-wrap">
                        <img id="product-main-image" src="{{ $product->thumbnail_path ? asset('storage/'.$product->thumbnail_path) : asset('assets/images/layout-2/product/1.jpg') }}" class="img-fluid product-main-image" alt="{{ $product->name }}">
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
                        @if($product->owner_type === 'vendor' && $product->vendor)
                            <div class="vendor-contact-card">
                                <h6 class="product-title">Vendor Details</h6>
                                <p class="mb-1"><strong>Shop:</strong> {{ $product->vendor->shop_name ?: $product->vendor->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $product->vendor->shop_email ?: $product->vendor->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $product->vendor->shop_phone ?: $product->vendor->phone ?: 'Not set' }}</p>
                                <p class="mb-2"><strong>Address:</strong> {{ $product->vendor->shop_address ?: 'Not set' }}</p>

                                @auth
                                    <form method="POST" action="{{ route('products.contact-vendor', $product) }}">
                                        @csrf
                                        <input class="form-control mb-2" name="subject" value="{{ old('subject') }}" placeholder="Subject">
                                        <textarea class="form-control mb-2" name="message" rows="3" required placeholder="Write your message to vendor">{{ old('message') }}</textarea>
                                        <button class="btn btn-normal" type="submit">contact vendor</button>
                                    </form>
                                @else
                                    <a class="btn btn-normal" href="{{ route('login') }}">login to contact vendor</a>
                                @endauth
                            </div>
                        @endif
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
    document.querySelectorAll('.product-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            const mainImage = document.getElementById('product-main-image');
            if (mainImage) mainImage.src = this.dataset.fullImage;
            document.querySelectorAll('.product-thumb').forEach(function (item) {
                item.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>
</body>
</html>
