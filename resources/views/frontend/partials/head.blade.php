<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="{{ $globalSiteInfo?->short_description ?? 'Multivendor ecommerce marketplace' }}">
<meta name="keywords" content="{{ $globalSiteInfo?->site_name ?? 'multivendor ecommerce marketplace' }}">
<meta name="author" content="{{ $globalSiteInfo?->site_name ?? 'Multivendor Ecommerce' }}">
<title>{{ $title ?? ($globalSiteInfo?->site_name ?? 'Multivendor Ecommerce') }}</title>
<link rel="icon" href="{{ \App\Support\PublicMedia::url($globalSiteInfo?->favicon_path, 'assets/images/favicon/favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ \App\Support\PublicMedia::url($globalSiteInfo?->favicon_path, 'assets/images/favicon/favicon.ico') }}" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="/assets/css/themify.css">
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="/assets/css/animate.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/assets/css/color2.css" media="screen" id="color">
<style>
  .frontend-site-logo{max-width:170px;max-height:64px;object-fit:contain}
  .marketplace-search-form{width:100%}
  .cart-block a{color:inherit}
  .cart-option-grid{display:grid;grid-template-columns:repeat(3,minmax(120px,1fr));gap:12px;margin-bottom:16px}
  .cart-option-grid .form-control{height:45px}
  .product-buttons form{display:inline-block;margin-right:10px}
  .cart-img{width:70px;height:70px;object-fit:contain;background:#f5f5f5}
  .cart-wrap,.checkout-wrap{padding:60px 0}
  .cart-actions form{display:inline-block}
  .summary-line{display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:10px 0}
  @media (max-width: 767px){.cart-option-grid{grid-template-columns:1fr}.product-buttons form{display:block;margin:0 0 10px}}
</style>
@stack('styles')
