<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $product->name }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="{{ $globalSiteInfo?->favicon_path ? asset('storage/'.$globalSiteInfo->favicon_path) : asset('assets/images/favicon/favicon.ico') }}">
    <link rel="stylesheet" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/themify.css">
    <link rel="stylesheet" href="/assets/css/slick.css">
    <link rel="stylesheet" href="/assets/css/slick-theme.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/color2.css">
</head>
<body>
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
                            @if($product->offer_price)<del>${{ number_format($product->price, 2) }}</del>@endif
                            <span>${{ number_format($product->offer_price ?: $product->price, 2) }}</span>
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
                                <a href="#" class="btn btn-normal">add to cart</a>
                                <a href="#" class="btn btn-normal">buy now</a>
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
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/slick.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>
