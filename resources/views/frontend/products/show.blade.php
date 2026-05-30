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
        .product-long-description{max-height:210px;overflow:hidden;position:relative}
        .product-long-description.collapsed:after{content:"";position:absolute;left:0;right:0;bottom:0;height:52px;background:linear-gradient(to bottom, rgba(248,248,248,0), #f8f8f8)}
        .description-toggle{border:0;background:transparent;color:#00baf2;font-weight:700;padding:8px 0;text-transform:capitalize;cursor:pointer}
        .related-product-img{width:100%;aspect-ratio:1/1;object-fit:cover;background:#f1f1f1}
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
                </div>
                <div class="col-lg-6 rtl-text">
                    <div class="product-right">
                        <h2>{{ $product->name }}</h2>
                        <h4>
                            @if($product->offer_price)<del>{{ \App\Support\Currency::format($product->price, $globalSiteInfo) }}</del>@endif
                            <span>{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</span>
                        </h4>
                        <div class="product-buttons border-product">
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
                        <div class="border-product">
                            <h6 class="product-title">Product Details</h6>
                            <div id="product-long-description" class="product-long-description collapsed">{!! $product->long_description ?: nl2br(e($product->short_description)) !!}</div>
                            <button id="description-toggle" class="description-toggle" type="button">see more</button>
                        </div>
                        <div class="product-description border-product">
                            <h6 class="product-title">SKU: {{ $product->sku ?: 'N/A' }}</h6>
                            <h6 class="product-title">Category: {{ $product->category?->name }}</h6>
                            <h6 class="product-title">Brand: {{ $product->brand?->name ?: 'N/A' }}</h6>
                            <h6 class="product-title">Stock: {{ $product->stock_quantity > 0 ? $product->stock_quantity : 'Stock Out Product' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if($relatedProducts->isNotEmpty())
<section class="section-py-space bg-light ratio_square product">
    <div class="custom-container">
        <div class="title1">
            <h4>Latest Products</h4>
            <h2 class="title-inner1">Related Products</h2>
        </div>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                @php($relatedImage = $relatedProduct->thumbnail_path ? asset('storage/' . $relatedProduct->thumbnail_path) : asset('assets/images/layout-2/product/1.jpg'))
                <div class="col-xl-3 col-lg-3 col-md-4 col-6 mb-4">
                    <div class="product-box">
                        <div class="product-imgbox">
                            <a href="{{ route('products.show', $relatedProduct) }}">
                                <img src="{{ $relatedImage }}" class="img-fluid related-product-img" alt="{{ $relatedProduct->name }}">
                            </a>
                            @if($relatedProduct->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif
                            @if($relatedProduct->is_new)<div class="new-label1"><div>new</div></div>@endif
                        </div>
                        <div class="product-detail detail-inline">
                            <a href="{{ route('products.show', $relatedProduct) }}"><h6 class="price-title">{{ $relatedProduct->name }}</h6></a>
                            <div class="price">{{ \App\Support\Currency::format($relatedProduct->offer_price ?: $relatedProduct->price, $globalSiteInfo) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
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
    const description = document.getElementById('product-long-description');
    const descriptionToggle = document.getElementById('description-toggle');
    if (description && descriptionToggle) {
        if (description.scrollHeight <= 215) {
            description.classList.remove('collapsed');
            descriptionToggle.style.display = 'none';
        }
        descriptionToggle.addEventListener('click', function () {
            const isCollapsed = description.classList.toggle('collapsed');
            description.style.maxHeight = isCollapsed ? '210px' : 'none';
            descriptionToggle.textContent = isCollapsed ? 'see more' : 'see less';
        });
    }
</script>
</body>
</html>
