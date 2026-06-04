<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => $globalSiteInfo?->site_name ?? 'Multivendor Ecommerce'])
</head>
<body class="bg-light ">

@include('frontend.partials.header')

@php
  $defaultHeroSlides = [
    ['title' => 'mi', 'title_highlight' => 'Mobile', 'subtitle' => 'fast and light', 'heading' => 'Pixel Perfect Deal Camera', 'button_text' => 'Shop Now', 'button_url' => '#', 'image_one_asset' => 'assets/images/layout-2/slider/1.1.png', 'image_two_asset' => 'assets/images/layout-2/slider/1.2.png'],
    ['title' => 'big', 'title_highlight' => 'Sale', 'subtitle' => 'now start at $99', 'heading' => '50% off', 'button_text' => 'Shop Now', 'button_url' => '#', 'image_one_asset' => 'assets/images/layout-2/slider/2.1.png', 'image_two_asset' => 'assets/images/layout-2/slider/2.2.png'],
    ['title' => 'camera', 'title_highlight' => 'Sale', 'subtitle' => 'now start at $79', 'heading' => '70% off today', 'button_text' => 'Shop Now', 'button_url' => '#', 'image_one_asset' => 'assets/images/layout-2/slider/3.2.png', 'image_two_asset' => 'assets/images/layout-2/slider/3.1.png'],
  ];
  $defaultCollectionBanners = [
    ['title' => 'women', 'subtitle' => 'fashion', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/1.jpg'],
    ['title' => 'camera', 'subtitle' => 'lenses', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/2.jpg'],
    ['title' => 'refrigerator', 'subtitle' => 'lG mini', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/3.jpg'],
  ];
  $defaultContentBlocks = [
    'discount' => ['subtitle' => 'Discount on every single item on our site.', 'title' => 'OMG! Just Look at the', 'highlight' => 'great Deals!', 'description' => 'How does it feel, when you see great discount deals for each product?'],
    'wide_banner' => ['subtitle' => 'save up to 30% off', 'title' => 'women', 'highlight' => 'fashion', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/7.jpg'],
    'deal_banner' => ['subtitle' => 'save up to 30% to 40% off', 'title' => 'omg! just look at the great deals!', 'button_text' => 'View more', 'button_url' => '#'],
    'coupon_tiles' => [
      ['title' => '10% off', 'url' => '#'], ['title' => 'under @99', 'url' => '#'], ['title' => 'free shipping', 'url' => '#'],
      ['title' => 'extra 10% off', 'url' => '#'], ['title' => '$79 cashback', 'url' => '#'], ['title' => '80% off', 'url' => '#'],
    ],
    'secondary_banners' => [
      ['title' => 'Leather', 'subtitle' => 'new bag', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/4.jpg'],
      ['title' => 'Nike', 'subtitle' => 'breeze', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/5.jpg'],
      ['title' => 'Printing 3D', 'subtitle' => 'USB moon', 'button_text' => 'shop now', 'button_url' => '#', 'image_asset' => 'assets/images/layout-2/collection-banner/6.jpg'],
    ],
    'hot_deal' => [
      'title' => "today's hot deal", 'product_title' => 'Simply dummy text of the printing.',
      'description' => 'It is a long established fact that a reader. It is a long established fact that a reader.',
      'price' => '$45.00', 'old_price' => '$50.30',
      'images' => [
        ['image_asset' => 'assets/images/layout-2/hot-deal/8.jpg'], ['image_asset' => 'assets/images/layout-2/hot-deal/7.jpg'],
        ['image_asset' => 'assets/images/layout-2/hot-deal/6.jpg'], ['image_asset' => 'assets/images/layout-2/hot-deal/5.jpg'],
      ],
    ],
    'testimonials' => [
      ['name' => 'mark jecno', 'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.', 'image_asset' => 'assets/images/testimonial/1.jpg'],
      ['name' => 'mark jecno', 'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.', 'image_asset' => 'assets/images/testimonial/2.jpg'],
      ['name' => 'mark jecno', 'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.', 'image_asset' => 'assets/images/testimonial/3.jpg'],
    ],
    'instagram' => collect(range(1, 8))->map(fn($i) => ['url' => '#', 'image_asset' => "assets/images/insta/{$i}.jpg"])->all(),
  ];
  $heroSlides = $globalHomeSection?->hero_slides ?: $defaultHeroSlides;
  $collectionBanners = $globalHomeSection?->collection_banners ?: $defaultCollectionBanners;
  $contentBlocks = array_replace_recursive($defaultContentBlocks, $globalHomeSection?->content_blocks ?: []);
@endphp

@if ($globalFrontendBrands->isNotEmpty())
<section class="brand-panel">
  <div class="brand-panel-box">
    <div class="brand-panel-contain ">
      <ul>
        <li><a href="#">top brand</a></li>
        <li><a>:</a></li>
        @foreach ($globalFrontendBrands as $brand)
          <li><a href="#">{{ $brand->name }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
</section>
@endif

<!--slider start-->
<section class="theme-slider b-g-white " id="theme-slider">
  <div class="custom-container">
    <div class="row">
      <div class="col">
        <div class="slide-1 no-arrow">
          @foreach ($heroSlides as $slideIndex => $slide)
            @php
              $fallbackSlide = $defaultHeroSlides[$slideIndex] ?? $defaultHeroSlides[0];
              $imageOne = \App\Support\PublicMedia::url($slide['image_one_path'] ?? null, $fallbackSlide['image_one_asset']);
              $imageTwo = \App\Support\PublicMedia::url($slide['image_two_path'] ?? null, $fallbackSlide['image_two_asset']);
            @endphp
            <div>
              <div class="slider-banner p-center slide-banner-1">
                <div class="slider-img">
                  <ul class="layout1-slide-{{ $slideIndex + 1 }}">
                    <li id="img-{{ ($slideIndex * 2) + 1 }}" class="{{ $slideIndex === 0 ? '' : 'slide-center' }}"><img src="{{ $imageOne }}" class="img-fluid" alt="slider"></li>
                    <li id="img-{{ ($slideIndex * 2) + 2 }}" class="slide-center"><img src="{{ $imageTwo }}" class="img-fluid" alt="slider"></li>
                  </ul>
                </div>
                <div class="slider-banner-contain">
                  <div>
                    <h1>{{ $slide['title'] ?? $fallbackSlide['title'] }}<span>{{ $slide['title_highlight'] ?? $fallbackSlide['title_highlight'] }}</span></h1>
                    <h4>{{ $slide['subtitle'] ?? $fallbackSlide['subtitle'] }}</h4>
                    <h2>{{ $slide['heading'] ?? $fallbackSlide['heading'] }}</h2>
                    <a class="btn btn-normal" href="{{ $slide['button_url'] ?? $fallbackSlide['button_url'] }}">
                      {{ $slide['button_text'] ?? $fallbackSlide['button_text'] }}
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
<!--slider end-->

<!--collection banner start-->
<section class="collection-banner section-pt-space b-g-white ">
  <div class="custom-container">
    <div class="row collection2">
      @foreach ($collectionBanners as $bannerIndex => $banner)
        @php
          $fallbackBanner = $defaultCollectionBanners[$bannerIndex] ?? $defaultCollectionBanners[0];
          $bannerImage = \App\Support\PublicMedia::url($banner['image_path'] ?? null, $fallbackBanner['image_asset']);
        @endphp
        <div class="col-md-4">
          <div class="collection-banner-main banner-1 {{ $bannerIndex === 0 ? ' p-right' : 'p-right' }}">
            <div class="collection-img">
              <img src="{{ $bannerImage }}" class="img-fluid bg-img  " alt="banner">
            </div>
            <div class="collection-banner-contain {{ $bannerIndex === 0 ? '' : ' ' }}">
              <div>
                <h3>{{ $banner['title'] ?? $fallbackBanner['title'] }}</h3>
                <h4>{{ $banner['subtitle'] ?? $fallbackBanner['subtitle'] }}</h4>
                <div class="shop">
                  <a href="{{ $banner['button_url'] ?? $fallbackBanner['button_url'] }}">
                    {{ $banner['button_text'] ?? $fallbackBanner['button_text'] }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
<!--collection banner end-->

<!--discount banner start-->
<section class="discount-banner">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="discount-banner-contain">
          <h2>{{ $contentBlocks['discount']['subtitle'] }}</h2>
          <h1><span>{{ $contentBlocks['discount']['title'] }}</span> <span>{{ $contentBlocks['discount']['highlight'] }}</span></h1>
          <div class="rounded-contain rounded-inverse">
            <div class="rounded-subcontain">
              {{ $contentBlocks['discount']['description'] }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--discount banner end-->

<!--tab product-->
<section class="section-pt-space">
  <div class="tab-product-main">
    <div class="tab-prodcut-contain">
      <ul class="tabs tab-title">
        <li class="current"><a href="tab-1">new product</a></li>
        <li><a href="tab-2">feature products</a></li>
        <li><a href="tab-3">best sellers</a></li>
        <li><a href="tab-4">on sale</a></li>
      </ul>
    </div>
  </div>
</section>
<section class="section-py-space ratio_square product">
  <div class="custom-container">
    <div class="row">
      <div class="col pr-0">
        <div class="theme-tab product mb--5">
          <div class="tab-content-cls">
            <div id="tab-1" class="tab-content active default">
              <div class="product-slide-6 product-m no-arrow">
                @forelse ($globalFrontendProducts as $product)
                  @php
                    $productImage = \App\Support\PublicMedia::url($product->thumbnail_path, 'assets/images/layout-2/product/1.jpg');
                    $displayPrice = $product->offer_price ?: $product->price;
                  @endphp
                  <div>
                    <div class="product-box">
                      <div class="product-imgbox">
                        <div class="product-front"><a href="{{ route('products.show', $product) }}"><img src="{{ $productImage }}" class="img-fluid" alt="{{ $product->name }}"></a></div>
                        <div class="product-back"><a href="{{ route('products.show', $product) }}"><img src="{{ $productImage }}" class="img-fluid" alt="{{ $product->name }}"></a></div>
                        <div class="product-icon icon-inline"><button onclick="openCart()"><i class="ti-bag"></i></button><a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart"></i></a><a href="{{ route('products.show', $product) }}" title="Quick View"><i class="ti-search"></i></a></div>
                        @if ($product->is_new)<div class="new-label1"><div>new</div></div>@endif
                        @if ($product->is_on_sale)<div class="on-sale1">on sale</div>@endif
                        @if ($product->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif
                      </div>
                      <div class="product-detail detail-inline">
                        <div class="detail-title">
                          <div class="detail-left"><div class="rating-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div><a href="{{ route('products.show', $product) }}"><h6 class="price-title">{{ $product->name }}</h6></a></div>
                          <div class="detail-right"><div class="check-price">@if($product->offer_price) {{ \App\Support\Currency::format($product->price, $globalSiteInfo) }} @endif</div><div class="price"><div class="price">{{ \App\Support\Currency::format($displayPrice, $globalSiteInfo) }}</div></div></div>
                        </div>
                      </div>
                    </div>
                  </div>
                @empty
                  <div><p>No products found.</p></div>
                @endforelse
              </div>
            </div>
            <div id="tab-2" class="tab-content"><div class="product-slide-6 product-m no-arrow">@foreach($globalFrontendProducts->where('is_featured', true) as $product)<div><div class="product-box"><div class="product-imgbox"><a href="{{ route('products.show', $product) }}"><img src="{{ \App\Support\PublicMedia::url($product->thumbnail_path, 'assets/images/layout-2/product/3.jpg') }}" class="img-fluid" alt="{{ $product->name }}"></a>@if ($product->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif</div><div class="product-detail detail-inline"><a href="{{ route('products.show', $product) }}"><h6 class="price-title">{{ $product->name }}</h6></a><div class="price">{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</div></div></div></div>@endforeach</div></div>
            <div id="tab-3" class="tab-content"><div class="product-slide-6 product-m no-arrow">@foreach($globalFrontendProducts->take(6) as $product)<div><div class="product-box"><div class="product-imgbox"><a href="{{ route('products.show', $product) }}"><img src="{{ \App\Support\PublicMedia::url($product->thumbnail_path, 'assets/images/layout-2/product/4.jpg') }}" class="img-fluid" alt="{{ $product->name }}"></a>@if ($product->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif</div><div class="product-detail detail-inline"><a href="{{ route('products.show', $product) }}"><h6 class="price-title">{{ $product->name }}</h6></a><div class="price">{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</div></div></div></div>@endforeach</div></div>
            <div id="tab-4" class="tab-content"><div class="product-slide-6 product-m no-arrow">@foreach($globalFrontendProducts->where('is_on_sale', true) as $product)<div><div class="product-box"><div class="product-imgbox"><a href="{{ route('products.show', $product) }}"><img src="{{ \App\Support\PublicMedia::url($product->thumbnail_path, 'assets/images/layout-2/product/5.jpg') }}" class="img-fluid" alt="{{ $product->name }}"></a>@if ($product->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif</div><div class="product-detail detail-inline"><a href="{{ route('products.show', $product) }}"><h6 class="price-title">{{ $product->name }}</h6></a><div class="price">{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</div></div></div></div>@endforeach</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- slider tab end -->

<!--collection banner start-->
<section class="collection-banner section-pb-space ">
  @php
    $wideBannerImage = \App\Support\PublicMedia::url(
      $contentBlocks['wide_banner']['image_path'] ?? null,
      $contentBlocks['wide_banner']['image_asset']
    );
  @endphp
  <div class="custom-container">
    <div class="row">
      <div class="col">
        <div class="collection-banner-main banner-5 p-center">
          <div class="collection-img">
            <img src="{{ $wideBannerImage }}" class="bg-img  " alt="banner">
          </div>
          <div class="collection-banner-contain ">
            <div class="sub-contain">
              <h3>{{ $contentBlocks['wide_banner']['subtitle'] }}</h3>
              <h4>{{ $contentBlocks['wide_banner']['title'] }}<span>{{ $contentBlocks['wide_banner']['highlight'] }}</span></h4>
              <div class="shop">
                <a class="btn btn-normal" href="{{ $contentBlocks['wide_banner']['button_url'] }}">
                  {{ $contentBlocks['wide_banner']['button_text'] }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--collection banner end-->

<!--deal banner start-->
<section class="deal-banner">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-lg-8">
        <div class="deal-banner-containe">
          <h2>
            {{ $contentBlocks['deal_banner']['subtitle'] }}
          </h2>
          <h1>
            {{ $contentBlocks['deal_banner']['title'] }}
          </h1>
        </div>
      </div>
      <div class="col-md-12 col-lg-4 ">
        <div class="deal-banner-containe">
          <diV class="deal-btn">
            <a class="btn-white" href="{{ $contentBlocks['deal_banner']['button_url'] }}">
              {{ $contentBlocks['deal_banner']['button_text'] }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--deal banner end-->

<!--rounded category start-->
<section class="rounded-category">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="slide-6 no-arrow">
          @foreach ($globalFrontendCategories->take(10) as $categoryIndex => $category)
            @php
              $roundedImage = \App\Support\PublicMedia::url($category->image_path, 'assets/images/layout-1/rounded-cat/' . (($categoryIndex % 7) + 1) . '.png');
            @endphp
            <div>
              <div class="category-contain">
                <a href="#">
                  <div class="img-wrapper">
                    <img src="{{ $roundedImage }}" alt="{{ $category->name }}" class="img-fluid">
                  </div>
                  <div>
                    <div class="btn-rounded">
                      {{ $category->name }}
                    </div>
                  </div>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
<!--rounded category end-->

<!--box categroy start-->
<section class="box-category section-py-space">
  <div class="container-fluid ">
    <div class="row">
      <div class="col pl-0">
        <div class="slide-10 no-arrow">
          @foreach($contentBlocks['coupon_tiles'] as $tile)
            <div>
              <a href="{{ $tile['url'] ?? '#' }}">
                <div class="box-category-contain">
                  <h4>{{ $tile['title'] ?? '' }}</h4>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
<!--box category end-->


<!--collection banner start-->
<section class="collection-banner section-py-space">
  <div class="container-fluid">
    <div class="row collection2">
      @foreach($contentBlocks['secondary_banners'] as $secondaryBanner)
        @php
          $secondaryBannerImage = \App\Support\PublicMedia::url(
            $secondaryBanner['image_path'] ?? null,
            $secondaryBanner['image_asset'] ?? 'assets/images/layout-2/collection-banner/4.jpg'
          );
        @endphp
        <div class="col-md-4">
          <div class="collection-banner-main banner-1 p-left">
            <div class="collection-img">
              <img src="{{ $secondaryBannerImage }}" class="img-fluid bg-img " alt="banner">
            </div>
            <div class="collection-banner-contain ">
              <div>
                <h3>{{ $secondaryBanner['title'] ?? '' }}</h3>
                <h4>{{ $secondaryBanner['subtitle'] ?? '' }}</h4>
                <div class="shop">
                  <a href="{{ $secondaryBanner['button_url'] ?? '#' }}">
                    {{ $secondaryBanner['button_text'] ?? 'shop now' }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
<!--collection banner end-->

<!--hot deal start-->
<section class="hot-deal b-g-white section-big-pb-space space-abjust">
  @php
    $hotDealImages = collect($contentBlocks['hot_deal']['images'] ?? [])->map(function ($image, $index) {
      return \App\Support\PublicMedia::url(
        $image['image_path'] ?? null,
        $image['image_asset'] ?? 'assets/images/layout-2/hot-deal/' . (8 - $index) . '.jpg'
      );
    })->values();
  @endphp
  <div class="custom-container">
    <div class="row hot-2">
      <div class="col-12">
        <!--title start-->
        <div class="title3 b-g-white text-left">
          <h4>{{ $contentBlocks['hot_deal']['title'] }}</h4>
        </div>
        <!--titel end-->
      </div>

      <div class="col-lg-9">
        <div class="slide-1 no-arrow">
          <div>
            <div class="hot-deal-contain deal-abjust">
              <div class="row hot-deal-subcontain">
                <div class="col-lg-4 col-md-4 ">
                  <div class="hotdeal-right-slick border-0">
                    @foreach($hotDealImages as $hotDealImage)
                      <div><img src="{{ $hotDealImage }}" alt="hot-deal" class="img-fluid  "></div>
                    @endforeach
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="hot-deal-center">
                    <div>
                      <div>
                        <h5>{{ $contentBlocks['hot_deal']['product_title'] }}</h5>
                      </div>
                      <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                      </div>
                      <div>
                        <p>
                          {{ $contentBlocks['hot_deal']['description'] }}
                        </p>
                        <div class="price">
                          <span>{{ $contentBlocks['hot_deal']['price'] }}</span>
                          <span>{{ $contentBlocks['hot_deal']['old_price'] }}</span>
                        </div>
                      </div>
                      <div class="timer">
                        <p id="demo">
                             <span>
                               25
                               <span>days</span>
                             </span>
                          <span>
                              46
                              <span>hrs</span>
                            </span>
                          <span>
                              12
                              <span>min</span>
                            </span>
                          <span>
                              03
                              <span>sec</span>
                            </span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 ">
                  <div class="hotdeal-right-nav">
                    @foreach($hotDealImages as $hotDealImage)
                      <div><img src="{{ $hotDealImage }}" alt="hot-dea" class="img-fluid  " ></div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="hot-deal-contain deal-abjust">
              <div class="row hot-deal-subcontain">
                <div class="col-lg-4 col-md-4 ">
                  <div class="hotdeal-right-slick border-0">
                    @foreach($hotDealImages as $hotDealImage)
                      <div><img src="{{ $hotDealImage }}" alt="hot-deal" class="img-fluid  "></div>
                    @endforeach
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="hot-deal-center">
                    <div>
                      <div>
                        <h5>{{ $contentBlocks['hot_deal']['product_title'] }}</h5>
                      </div>
                      <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                      </div>
                      <div>
                        <p>
                          {{ $contentBlocks['hot_deal']['description'] }}
                        </p>
                        <div class="price">
                          <span>{{ $contentBlocks['hot_deal']['price'] }}</span>
                          <span>{{ $contentBlocks['hot_deal']['old_price'] }}</span>
                        </div>
                      </div>
                      <div class="timer">
                        <p id="demo1">
                           <span>
                             25
                             <span>days</span>
                           </span>
                          <span>
                            46
                            <span>hrs</span>
                          </span>
                          <span>
                            12
                            <span>min</span>
                          </span>
                          <span>
                            03
                            <span>sec</span>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 ">
                  <div class="hotdeal-right-nav">
                    @foreach($hotDealImages as $hotDealImage)
                      <div><img src="{{ $hotDealImage }}" alt="hot-dea" class="img-fluid  " ></div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="slide-1-section no-arrow">
          <div>
            <div class="media-banner border-0">
              <div class="media-banner-box">
                <div class="media-heading">
                  <h5>New Products</h5>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/1.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/2.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/2.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media-view">
                  <h5>View More</h5>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="media-banner  border-0">
              <div class="media-banner-box">
                <div class="media-heading">
                  <h5>Hot deal</h5>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/3.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/4.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/3.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media-view">
                  <h5>View More</h5>
                </div>
              </div>
            </div>
          </div>
          <div >
            <div class="media-banner  border-0">
              <div class="media-banner-box">
                <div class="media-heading">
                  <h5>Best Sellers</h5>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/1.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/2.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media">
                  <img src="/assets/images/layout-1/media-banner/1.jpg" class="img-fluid  " alt="banner">
                  <div class="media-body">
                    <div class="media-contant">
                      <div>
                        <div class="rating">
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                          <i class="fa fa-star" ></i>
                        </div>
                        <p>
                          Generator
                          on Internet.
                        </p>
                        <h6>$153.00</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="media-banner-box">
                <div class="media-view">
                  <h5>View More</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--hot deal start-->

<!--testimonial start-->
<section class="testimonial testimonial-inverse">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="slide-1 no-arrow">
          @foreach($contentBlocks['testimonials'] as $testimonial)
            @php
              $testimonialImage = \App\Support\PublicMedia::url(
                $testimonial['image_path'] ?? null,
                $testimonial['image_asset'] ?? 'assets/images/testimonial/1.jpg'
              );
            @endphp
            <div>
              <div class="testimonial-contain">
                <div class="media">
                  <div class="testimonial-img">
                    <img src="{{ $testimonialImage }}" class="img-fluid rounded-circle  " alt="testimonial">
                  </div>
                  <div class="media-body">
                    <h5>{{ $testimonial['name'] ?? '' }}</h5>
                    <p>{{ $testimonial['description'] ?? '' }}</p>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
<!--testimonial end-->

<!--title start-->
<div class="title1 section-my-space">
  <h4>Special Products</h4>
</div>
<!--title end-->

<!--product start-->
<section class="product section-pb-space mb--5">
  <div class="custom-container">
    <div class="row">
      <div class="col pr-0">
        <div class="product-slide-6 no-arrow">
          @foreach($globalFrontendProducts->where('is_featured', true)->take(8) as $product)
            @php
              $specialImage = \App\Support\PublicMedia::url($product->thumbnail_path, 'assets/images/layout-2/product/1.jpg');
            @endphp
            <div>
              <div class="product-box">
                <div class="product-imgbox">
                  <div class="product-front"><a href="{{ route('products.show', $product) }}"><img src="{{ $specialImage }}" class="img-fluid" alt="{{ $product->name }}"></a></div>
                  <div class="product-back"><a href="{{ route('products.show', $product) }}"><img src="{{ $specialImage }}" class="img-fluid" alt="{{ $product->name }}"></a></div>
                  @if($product->is_new)<div class="new-label1"><div>new</div></div>@endif
                  @if($product->is_on_sale)<div class="on-sale1">on sale</div>@endif
                  @if($product->stock_quantity <= 0)<div class="on-sale1">stock out</div>@endif
                </div>
                <div class="product-detail detail-inline">
                  <div class="detail-title">
                    <div class="detail-left">
                      <div class="rating-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                      <a href="{{ route('products.show', $product) }}"><h6 class="price-title">{{ $product->name }}</h6></a>
                    </div>
                    <div class="detail-right">
                      @if($product->offer_price)<div class="check-price">{{ \App\Support\Currency::format($product->price, $globalSiteInfo) }}</div>@endif
                      <div class="price">{{ \App\Support\Currency::format($product->offer_price ?: $product->price, $globalSiteInfo) }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
<!--product end-->

@if(false)
<!--title start-->
<div class="title1 section-my-space">
  <h4>Special Products</h4>
</div>
<!--title end-->

<!--product start-->
<section class="product section-pb-space mb--5">
  <div class="custom-container">
    <div class="row">
      <div class="col pr-0">
        <div class="product-slide-6 no-arrow">
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/1.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a1.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button onclick="openCart()">
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>
                </div>
                <div class="new-label1">
                  <div>new</div>
                </div>
                <div class="on-sale1">
                  on sale
                </div>
              </div>
              <div class="product-detail detail-inline ">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/2.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a2.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button onclick="openCart()">
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>

                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/3.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a3.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button  onclick="openCart()" type="button" >
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>

                </div>
                <div class="new-label1">
                  <div>new</div>
                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/4.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a4.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button  onclick="openCart()" type="button" >
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/5.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a5.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button  onclick="openCart()" type="button" >
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>

                </div>
                <div class="new-label1">
                  <div>new</div>
                </div>
                <div class="on-sale1">
                  on sale
                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/6.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a6.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button  onclick="openCart()" type="button" >
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/7.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a7.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button  onclick="openCart()" type="button" >
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="product-box">
              <div class="product-imgbox">
                <div class="product-front">
                  <img src="/assets/images/layout-2/product/8.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-back">
                  <img src="/assets/images/layout-2/product/a8.jpg" class="img-fluid  " alt="product">
                </div>
                <div class="product-icon icon-inline">
                  <button  onclick="openCart()" type="button" >
                    <i class="ti-bag" ></i>
                  </button>
                  <a href="javascript:void(0)" title="Add to Wishlist">
                    <i class="ti-heart" aria-hidden="true"></i>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                  </a>
                  <a href="compare.html" title="Compare">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="product-detail detail-inline">
                <div class="detail-title">
                  <div class="detail-left">
                    <div class="rating-star">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <a href="">
                      <h6 class="price-title">
                        reader will be distracted.
                      </h6>
                    </a>
                  </div>
                  <div class="detail-right">
                    <div class="check-price">
                      $ 56.21
                    </div>
                    <div class="price">
                      <div class="price">
                        $ 24.05
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
  </div>
</section>
<!--product end-->
@endif

<!--instagram start-->
<section class="instagram">
  <div class="container-fluid">
    <div class="row">
      <div class="col p-0">
        <div class="insta-contant1 no-arrow">
          <div class="slide-7">
            @foreach($contentBlocks['instagram'] as $instagram)
              @php
                $instagramImage = \App\Support\PublicMedia::url(
                  $instagram['image_path'] ?? null,
                  $instagram['image_asset'] ?? 'assets/images/insta/1.jpg'
                );
              @endphp
              <div>
                <a href="{{ $instagram['url'] ?? '#' }}">
                  <div class="instagram-box">
                    <img src="{{ $instagramImage }}" class="img-fluid  " alt="insta">
                    <div class="insta-cover">
                      <i class="fa fa-instagram" ></i>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
          <div class="insta-sub-contant1">
            <div class="insta-title">
              <h4><span>#</span>INSTAGRAM</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--instagra end-->

<!--contact banner start-->
<section class="contact-banner contact-banner-inverse">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="contact-banner-contain">
          <div class="contact-banner-img"><img src="/assets/images/layout-1/call-img.png" class="  img-fluid" alt="call-banner"></div>
          <div> <h3>if you have any question please call us</h3></div>
          <div><h2>123-456-7890</h2></div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--contact banner end-->
<!-- tap to top -->
<div class="tap-top">
  <div>
    <i class="fa fa-angle-double-up"></i>
  </div>
</div>
<!-- tap to top End -->

<!-- Add to cart bar -->
<div id="cart_side" class=" add_to_cart top">
  <a href="javascript:void(0)" class="overlay" onclick="closeCart()"></a>
  <div class="cart-inner">
    <div class="cart_top">
      <h3>my cart</h3>
      <div class="close-cart">
        <a href="javascript:void(0)" onclick="closeCart()">
          <i class="fa fa-times" aria-hidden="true"></i>
        </a>
      </div>
    </div>
    <div class="cart_media">
      <ul class="cart_product">
        <li>
          <div class="media">
            <a href="#">
              <img alt="" class="mr-3" src="/assets/images/layout-2/product/1.jpg">
            </a>
            <div class="media-body">
              <a href="#">
                <h4>item name</h4>
              </a>
              <h4>
                <span>1 x $ 299.00</span>
              </h4>
            </div>
          </div>
          <div class="close-circle">
            <a href="#">
              <i class="ti-trash" aria-hidden="true"></i>
            </a>
          </div>
        </li>
        <li>
          <div class="media">
            <a href="#">
              <img alt="" class="mr-3" src="/assets/images/layout-2/product/a1.jpg">
            </a>
            <div class="media-body">
              <a href="#">
                <h4>item name</h4>
              </a>
              <h4>
                <span>1 x $ 299.00</span>
              </h4>
            </div>
          </div>
          <div class="close-circle">
            <a href="#">
              <i class="ti-trash" aria-hidden="true"></i>
            </a>
          </div>
        </li>
        <li>
          <div class="media">
            <a href="#"><img alt="" class="mr-3" src="/assets/images/layout-2/product/1.jpg"></a>
            <div class="media-body">
              <a href="#">
                <h4>item name</h4>
              </a>
              <h4><span>1 x $ 299.00</span></h4>
            </div>
          </div>
          <div class="close-circle">
            <a href="#">
              <i class="ti-trash" aria-hidden="true"></i>
            </a>
          </div>
        </li>
      </ul>
      <ul class="cart_total">
        <li>
          <div class="total">
            <h5>subtotal : <span>$299.00</span></h5>
          </div>
        </li>
        <li>
          <div class="buttons">
            <a href="cart.html" class="btn btn-normal btn-xs view-cart">view cart</a>
            <a href="#" class="btn btn-normal btn-xs checkout">checkout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Add to cart bar end-->

<!--Newsletter modal popup start-->
@if($globalSiteInfo?->newsletter_popup_enabled)
  @php
    $newsletterPopupImage = \App\Support\PublicMedia::url(
      $globalSiteInfo->newsletter_popup_image_path,
      'assets/images/layout-2/product/a1.jpg'
    );
  @endphp
<div class="modal fade bd-example-modal-lg theme-modal" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="news-latter">
          <div class="modal-bg" style="background-image:url('{{ $newsletterPopupImage }}');">
            <div class="offer-content">
              <div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h2>{{ $globalSiteInfo->newsletter_popup_title ?: 'Newsletter' }}</h2>
                <p>{!! nl2br(e($globalSiteInfo->newsletter_popup_description ?: 'Subscribe to our website mailing list and get a special offer, just for you!')) !!}</p>
                <form action="https://pixelstrap.us19.list-manage.com/subscribe/post?u=5a128856334b598b395f1fc9b&amp;id=082f74cbda" class="auth-form needs-validation" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank">
                  <div class="form-group mx-sm-3">
                    <input type="email" class="form-control" name="EMAIL" id="mce-EMAIL" placeholder="Enter your email" required="required">
                    <button type="submit" class="btn btn-theme btn-normal btn-sm " id="mc-submit">{{ $globalSiteInfo->newsletter_popup_button_text ?: 'Subscribe' }}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<!--Newsletter Modal popup end-->

<!-- Quick-view modal popup start-->
<div class="modal fade bd-example-modal-lg theme-modal" id="quick-view" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content quick-view-modal">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="row">
          <div class="col-lg-6 col-xs-12">
            <div class="quick-view-img"><img src="/assets/images/layout-2/product/a1.jpg" alt="" class="img-fluid "></div>
          </div>
          <div class="col-lg-6 rtl-text">
            <div class="product-right">
              <h2>Women Pink Shirt</h2>
              <h3>$32.96</h3>
              <ul class="color-variant">
                <li class="bg-light0"></li>
                <li class="bg-light1"></li>
                <li class="bg-light2"></li>
              </ul>
              <div class="border-product">
                <h6 class="product-title">product details</h6>
                <p>Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium</p>
              </div>
              <div class="product-description border-product">
                <div class="size-box">
                  <ul>
                    <li class="active"><a href="#">s</a></li>
                    <li><a href="#">m</a></li>
                    <li><a href="#">l</a></li>
                    <li><a href="#">xl</a></li>
                  </ul>
                </div>
                <h6 class="product-title">quantity</h6>
                <div class="qty-box">
                  <div class="input-group"><span class="input-group-prepend"><button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i></button> </span>
                    <input type="text" name="quantity" class="form-control input-number" value="1"> <span class="input-group-prepend"><button type="button" class="btn quantity-right-plus" data-type="plus" data-field=""><i class="ti-angle-right"></i></button></span></div>
                </div>
              </div>
              <div class="product-buttons"><a href="#" class="btn btn-normal">add to cart</a> <a href="#" class="btn btn-normal">view detail</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Quick-view modal popup end-->




<!-- My account bar start-->
<div id="myAccount" class="add_to_cart right account-bar">
  <a href="javascript:void(0)" class="overlay" onclick="closeAccount()"></a>
  <div class="cart-inner">
    <div class="cart_top">
      <h3>my account</h3>
      <div class="close-cart">
        <a href="javascript:void(0)" onclick="closeAccount()">
          <i class="fa fa-times" aria-hidden="true"></i>
        </a>
      </div>
    </div>
    <form class="theme-form">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" class="form-control" id="email" placeholder="Email" required="">
      </div>
      <div class="form-group">
        <label for="review">Password</label>
        <input type="password" class="form-control" id="review" placeholder="Enter your password" required="">
      </div>
      <div class="form-group">
        <a href="#" class="btn btn-rounded btn-block ">Login</a>
      </div>
      <div>
        <h5 class="forget-class"><a href="forget-pwd.html" class="d-block">forget password?</a></h5>
        <h6 class="forget-class"><a href="{{ route('register') }}" class="d-block">new to store? Signup now</a></h6>
      </div>
    </form>
  </div>
</div>
<!-- Add to account bar end-->

<!-- Add to wishlist bar -->
<div id="wishlist_side" class="add_to_cart right">
  <a href="javascript:void(0)" class="overlay" onclick="closeWishlist()"></a>
  <div class="cart-inner">
    <div class="cart_top">
      <h3>my wishlist</h3>
      <div class="close-cart">
        <a href="javascript:void(0)" onclick="closeWishlist()">
          <i class="fa fa-times" aria-hidden="true"></i>
        </a>
      </div>
    </div>
    <div class="cart_media">
      <ul class="cart_product">
        <li>
          <div class="media">
            <a href="#">
              <img alt="" class="mr-3" src="/assets/images/layout-1/media-banner/1.jpg">
            </a>
            <div class="media-body">
              <a href="#">
                <h4>item name</h4>
              </a>
              <h4>
                <span>sm</span>
                <span>, blue</span>
              </h4>
              <h4>
                <span>$ 299.00</span>
              </h4>
            </div>
          </div>
          <div class="close-circle">
            <a href="#">
              <i class="ti-trash" aria-hidden="true"></i>
            </a>
          </div>
        </li>
        <li>
          <div class="media">
            <a href="#">
              <img alt="" class="mr-3" src="/assets/images/layout-1/media-banner/2.jpg">
            </a>
            <div class="media-body">
              <a href="#">
                <h4>item name</h4>
              </a>
              <h4>
                <span>sm</span>
                <span>, blue</span>
              </h4>
              <h4>
                <span>$ 299.00</span>
              </h4>
            </div>
          </div>
          <div class="close-circle">
            <a href="#">
              <i class="ti-trash" aria-hidden="true"></i>
            </a>
          </div>
        </li>
        <li>
          <div class="media">
            <a href="#"><img alt="" class="mr-3" src="/assets/images/layout-1/media-banner/3.jpg"></a>
            <div class="media-body">
              <a href="#"><h4>item name</h4></a>
              <h4>
                <span>sm</span>
                <span>, blue</span>
              </h4>
              <h4><span>$ 299.00</span></h4>
            </div>
          </div>
          <div class="close-circle">
            <a href="#">
              <i class="ti-trash" aria-hidden="true"></i>
            </a>
          </div>
        </li>
      </ul>
      <ul class="cart_total">
        <li>
          <div class="total">
            <h5>subtotal : <span>$299.00</span></h5>
          </div>
        </li>
        <li>
          <div class="buttons">
            <a href="wishlist.html" class="btn btn-normal btn-block  view-cart">view wislist</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Add to wishlist bar end-->

<!-- add to  setting bar  start-->
<div id="mySetting" class="add_to_cart right">
  <a href="javascript:void(0)" class="overlay" onclick="closeSetting()"></a>
  <div class="cart-inner">
    <div class="cart_top">
      <h3>my setting</h3>
      <div class="close-cart">
        <a href="javascript:void(0)" onclick="closeSetting()">
          <i class="fa fa-times" aria-hidden="true"></i>
        </a>
      </div>
    </div>
    <div class="setting-block">
      <div >
        <h5>language</h5>
        <ul>
          <li><a href="#">english</a></li>
          <li><a href="#">french</a></li>
        </ul>
        <h5>currency</h5>
        <ul>
          <li><a href="#">uro</a></li>
          <li><a href="#">rupees</a></li>
          <li><a href="#">pound</a></li>
          <li><a href="#">doller</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- facebook chat section end -->

<!-- notification product -->
<div class="product-notification" id="dismiss">
  <span  onclick="dismiss();" class="close" aria-hidden="true">×</span>
  <div class="media">
    <img class="mr-2" src="/assets/images/layout-1/product/5.jpg" alt="Generic placeholder image">
    <div class="media-body">
      <h5 class="mt-0 mb-1">Latest trending</h5>
      Cras sit amet nibh libero, in gravida nulla.
    </div>
  </div>
</div>
<!-- notification product -->

<!-- latest jquery-->
@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- slick js-->
<script src="/assets/js/slick.js"></script>

<!-- popper js-->
<script src="/assets/js/popper.min.js" ></script>

<!-- Timer js-->
<script src="/assets/js/menu.js"></script>

<!-- Bootstrap js-->
<script src="/assets/js/bootstrap.js"></script>

<!-- Bootstrap js-->
<script src="/assets/js/bootstrap-notify.min.js"></script>

<!-- Theme js-->
<script src="/assets/js/script.js"></script>
<script src="/assets/js/slider-animat.js"></script>
<script src="/assets/js/timer.js"></script>
<script src="/assets/js/modal.js"></script>
</body>
</html>


