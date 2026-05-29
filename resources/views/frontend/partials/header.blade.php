@php
  $cartQuantity = collect(session('cart', []))->sum('quantity');
  $currencyCode = strtoupper($globalSiteInfo?->currency_code ?? 'USD');
@endphp
<div class="loader-wrapper">
  <div><img src="/assets/images/loader.gif" alt="loader"></div>
</div>
<header>
  <div class="mobile-fix-option"></div>
  <div class="top-header">
    <div class="custom-container">
      <div class="row">
        <div class="col-xl-5 col-md-7 col-sm-6">
          <div class="top-header-left">
            <div class="shpping-order"><h6>free shipping on order over {{ \App\Support\Currency::format(99, $globalSiteInfo) }}</h6></div>
            <div class="app-link">
              <h6>Download app</h6>
              <ul>
                <li><a><i class="fa fa-apple"></i></a></li>
                <li><a><i class="fa fa-android"></i></a></li>
                <li><a><i class="fa fa-windows"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-xl-7 col-md-5 col-sm-6">
          <div class="top-header-right">
            <div class="top-menu-block">
              <ul>
                <li><a href="{{ route('cart.index') }}">cart</a></li>
                <li><a href="{{ route('checkout.index') }}">checkout</a></li>
                <li><a href="#">help & contact</a></li>
                <li><a href="#">todays deal</a></li>
                <li><a href="#">track order</a></li>
              </ul>
            </div>
            <div class="language-block">
              <div class="language-dropdown">
                <span class="language-dropdown-click">english <i class="fa fa-angle-down" aria-hidden="true"></i></span>
                <ul class="language-dropdown-open">
                  <li><a href="#">english</a></li>
                </ul>
              </div>
              <div class="curroncy-dropdown">
                <span class="curroncy-dropdown-click">{{ strtolower($currencyCode) }} <i class="fa fa-angle-down" aria-hidden="true"></i></span>
                <ul class="curroncy-dropdown-open">
                  <li><a href="#">{{ $globalSiteInfo?->currency_symbol ?? '$' }} {{ strtolower($currencyCode) }}</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="layout-header2">
    <div class="container">
      <div class="col-md-12">
        <div class="main-menu-block">
          <div class="sm-nav-block">
            <span class="sm-nav-btn"><i class="fa fa-bars"></i></span>
            <ul class="nav-slide">
              <li><div class="nav-sm-back">back <i class="fa fa-angle-right pl-2"></i></div></li>
              @foreach ($globalFrontendCategories->take(12) as $category)
                <li><a href="#">{{ $category->name }}</a></li>
              @endforeach
            </ul>
          </div>
          <div class="logo-block">
            <a href="{{ route('home') }}"><img src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" class="img-fluid frontend-site-logo" alt="{{ $globalSiteInfo?->site_name ?? 'logo' }}"></a>
          </div>
          <div class="input-block">
            <div class="input-box">
              <form class="big-deal-form">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="search"><i class="fa fa-search"></i></span></div>
                  <input type="text" class="form-control" placeholder="Search a Product">
                  <div class="input-group-prepend">
                    <select>
                      <option>All Category</option>
                      @foreach ($globalFrontendCategories->take(8) as $category)
                        <option>{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <a class="cart-block cart-hover-div" href="{{ route('cart.index') }}">
            <div class="cart">
              <span class="cart-product">{{ $cartQuantity }}</span>
              <ul><li class="mobile-cart"><i class="icon-shopping-cart"></i></li></ul>
            </div>
            <div class="cart-item"><h5>shopping</h5><h5>cart</h5></div>
          </a>
          <div class="menu-nav"><span class="toggle-nav"><i class="fa fa-bars"></i></span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="category-header-2">
    <div class="custom-container">
      <div class="row">
        <div class="col">
          <div class="navbar-menu">
            <div class="category-left">
              <div class="nav-block">
                <div class="nav-left">
                  <nav class="navbar" data-toggle="collapse" data-target="#navbarToggleExternalContent">
                    <button class="navbar-toggler" type="button"><span class="navbar-icon"><i class="fa fa-arrow-down"></i></span></button>
                    <h5 class="mb-0 text-white title-font">Shop by category</h5>
                  </nav>
                  <div class="collapse nav-desk" id="navbarToggleExternalContent">
                    <ul class="nav-cat title-font">
                      @foreach ($globalFrontendCategories->take(12) as $categoryIndex => $category)
                        @php($navImage = $category->image_path ? asset('storage/' . $category->image_path) : asset('assets/images/layout-1/nav-img/' . str_pad(($categoryIndex % 12) + 1, 2, '0', STR_PAD_LEFT) . '.png'))
                        <li><img src="{{ $navImage }}" alt="category-product"> <a href="#">{{ $category->name }}</a></li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="menu-block">
                <nav id="main-nav">
                  <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                  <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                    <li><div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div></li>
                    <li><a href="{{ route('home') }}" class="dark-menu-item">Home</a></li>
                    <li><a href="#" class="dark-menu-item">Shop</a></li>
                    <li><a href="#" class="dark-menu-item">Product</a></li>
                    <li><a href="#" class="dark-menu-item">Features</a></li>
                    <li><a href="#" class="dark-menu-item">Pages</a></li>
                    <li><a href="#" class="dark-menu-item">Blog</a></li>
                  </ul>
                </nav>
              </div>
            </div>
            <div class="category-right">
              <div class="contact-block">
                <div><i class="icon-user"></i></div>
              </div>
              <div class="contact-block">
                <div><i class="icon-heart"></i></div>
                <div><span>0 item</span><h5>wishlist</h5></div>
              </div>
              <div class="gift-block"><div class="grif-icon"><i class="icon-gift"></i></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
