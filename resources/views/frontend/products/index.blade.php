<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Shop'])
    <style>
        .shop-product-img{width:100%;aspect-ratio:1/1;object-fit:cover;background:#f1f1f1}
        .shop-sidebar{background:#fff;border:1px solid #e6e6e6;padding:20px}
        .shop-sidebar a{display:block;color:#444;padding:8px 0;border-bottom:1px solid #f2f2f2}
        .shop-sidebar a.active,.shop-sidebar a:hover{color:#00baf2}
        .shop-filter-bar{background:#fff;border:1px solid #e6e6e6;padding:16px;margin-bottom:20px}
        .shop-card{background:#fff;margin-bottom:24px}
        .shop-card .product-detail{padding:14px}
    </style>
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>Shop</h2>
                <ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a>shop</a></li></ul>
            </div>
        </div>
    </div>
</section>
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <div class="row">
            <div class="col-lg-3">
                <div class="shop-sidebar">
                    <h4 class="mb-3">Categories</h4>
                    <a href="{{ route('shop.index', request()->except('category_id', 'page')) }}" @class(['active' => !request('category_id')])>All Category</a>
                    @foreach($categories as $category)
                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category_id' => $category->id])) }}" @class(['active' => request('category_id') == $category->id])>{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-9">
                <form class="shop-filter-bar" method="GET" action="{{ route('shop.index') }}">
                    <div class="row align-items-end">
                        <div class="col-md-6 form-group mb-md-0">
                            <label>Search Product</label>
                            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search by name, SKU, description">
                        </div>
                        <div class="col-md-4 form-group mb-md-0">
                            <label>Category</label>
                            <select class="form-control" name="category_id">
                                <option value="">All Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-normal btn-block" type="submit">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    @forelse($products as $product)
                        @php($image = \App\Support\PublicMedia::url($product->thumbnail_path, 'assets/images/layout-2/product/1.jpg'))
                        <div class="col-xl-4 col-md-6">
                            <div class="product-box shop-card">
                                <div class="product-imgbox">
                                    <a href="{{ route('products.show', $product) }}"><img src="{{ $image }}" class="img-fluid shop-product-img" alt="{{ $product->name }}"></a>
                                    @if($product->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif
                                    @if($product->is_new)<div class="new-label1"><div>new</div></div>@endif
                                </div>
                                <div class="product-detail detail-inline">
                                    <a href="{{ route('products.show', $product) }}"><h6 class="price-title">{{ $product->name }}</h6></a>
                                    <p class="mb-1">{{ $product->category?->name ?: 'Uncategorized' }}</p>
                                    <div class="price">
                                        @if($product->offer_price)<del>{{ \App\Support\Currency::format($product->price, $globalSiteInfo) }}</del>@endif
                                        {{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center bg-white p-5">
                                <h4>No products found.</h4>
                                <a class="btn btn-normal mt-3" href="{{ route('shop.index') }}">View All Products</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{ $products->links() }}
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
