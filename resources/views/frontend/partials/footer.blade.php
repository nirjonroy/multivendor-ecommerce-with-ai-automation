<footer class="footer-2">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="footer-main-contian">
          <div class="row">
            <div class="col-lg-4 col-md-12">
              <div class="footer-left">
                <div class="footer-logo">
                  <a href="{{ route('home') }}"><img src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" class="img-fluid frontend-site-logo" alt="{{ $globalSiteInfo?->site_name ?? 'logo' }}"></a>
                </div>
                <div class="footer-detail">
                  <p>{{ $globalSiteInfo?->short_description ?? 'A modern multivendor ecommerce marketplace.' }}</p>
                  <ul class="paymant-bottom">
                    <li><a href="#"><img src="/assets/images/layout-1/pay/1.png" class="img-fluid" alt="payment"></a></li>
                    <li><a href="#"><img src="/assets/images/layout-1/pay/2.png" class="img-fluid" alt="payment"></a></li>
                    <li><a href="#"><img src="/assets/images/layout-1/pay/3.png" class="img-fluid" alt="payment"></a></li>
                    <li><a href="#"><img src="/assets/images/layout-1/pay/4.png" class="img-fluid" alt="payment"></a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-8 col-md-12">
              <div class="footer-right">
                <div class="row">
                  <div class="col-md-4">
                    <div class="footer-box">
                      <div class="footer-title"><h5>my account</h5></div>
                      <div class="footer-contant">
                        <ul>
                          <li><a href="{{ route('login') }}">login</a></li>
                          <li><a href="{{ route('register') }}">register</a></li>
                          <li><a href="{{ route('cart.index') }}">cart</a></li>
                          <li><a href="{{ route('checkout.index') }}">checkout</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="footer-box">
                      <div class="footer-title"><h5>categories</h5></div>
                      <div class="footer-contant">
                        <ul>
                          @foreach($globalFrontendCategories->take(6) as $category)
                            <li><a href="#">{{ $category->name }}</a></li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="footer-box footer-contact-box">
                      <div class="footer-title"><h5>contact us</h5></div>
                      <div class="footer-contant">
                        <ul class="contact-list">
                          @if($globalSiteInfo?->address)<li><i class="fa fa-map-marker"></i>{{ $globalSiteInfo->address }}</li>@endif
                          @if($globalSiteInfo?->contact_phone)<li><i class="fa fa-phone"></i>{{ $globalSiteInfo->contact_phone }}</li>@endif
                          @if($globalSiteInfo?->contact_email)<li><i class="fa fa-envelope-o"></i>{{ $globalSiteInfo->contact_email }}</li>@endif
                        </ul>
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
  <div class="sub-footer">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-md-6 col-sm-12">
          <div class="footer-end"><p><i class="fa fa-copyright" aria-hidden="true"></i> {{ date('Y') }} {{ $globalSiteInfo?->site_name ?? 'Bigdeal' }}</p></div>
        </div>
        <div class="col-xl-6 col-md-6 col-sm-12">
          <div class="payment-card-bottom">
            <ul>
              <li><a href="#"><img src="/assets/images/layout-1/pay/1.png" alt="payment"></a></li>
              <li><a href="#"><img src="/assets/images/layout-1/pay/2.png" alt="payment"></a></li>
              <li><a href="#"><img src="/assets/images/layout-1/pay/3.png" alt="payment"></a></li>
              <li><a href="#"><img src="/assets/images/layout-1/pay/4.png" alt="payment"></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<script>
  (function () {
    function hideFrontendLoader() {
      var loader = document.querySelector('.loader-wrapper');
      if (loader) {
        loader.style.display = 'none';
      }
    }
    if (document.readyState === 'complete') {
      hideFrontendLoader();
      return;
    }
    document.addEventListener('DOMContentLoaded', hideFrontendLoader);
    window.addEventListener('load', hideFrontendLoader);
    window.setTimeout(hideFrontendLoader, 800);
  })();
</script>
