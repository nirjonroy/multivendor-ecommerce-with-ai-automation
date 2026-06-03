@php
    $defaultSlides = [
        ['title' => 'mi', 'title_highlight' => 'Mobile', 'subtitle' => 'fast and light', 'heading' => 'Pixel Perfect Deal Camera', 'button_text' => 'Shop Now', 'button_url' => '#', 'image_one_path' => null, 'image_two_path' => null],
        ['title' => 'big', 'title_highlight' => 'Sale', 'subtitle' => 'now start at $99', 'heading' => '50% off', 'button_text' => 'Shop Now', 'button_url' => '#', 'image_one_path' => null, 'image_two_path' => null],
        ['title' => 'camera', 'title_highlight' => 'Sale', 'subtitle' => 'now start at $79', 'heading' => '70% off today', 'button_text' => 'Shop Now', 'button_url' => '#', 'image_one_path' => null, 'image_two_path' => null],
    ];
    $defaultBanners = [
        ['title' => 'women', 'subtitle' => 'fashion', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
        ['title' => 'camera', 'subtitle' => 'lenses', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
        ['title' => 'refrigerator', 'subtitle' => 'lG mini', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
    ];
    $defaultContentBlocks = [
        'discount' => ['subtitle' => 'Discount on every single item on our site.', 'title' => 'OMG! Just Look at the', 'highlight' => 'great Deals!', 'description' => 'How does it feel, when you see great discount deals for each product?'],
        'wide_banner' => ['subtitle' => 'save up to 30% off', 'title' => 'women', 'highlight' => 'fashion', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
        'deal_banner' => ['subtitle' => 'save up to 30% to 40% off', 'title' => 'omg! just look at the great deals!', 'button_text' => 'View more', 'button_url' => '#'],
        'coupon_tiles' => [
            ['title' => '10% off', 'url' => '#'], ['title' => 'under @99', 'url' => '#'], ['title' => 'free shipping', 'url' => '#'],
            ['title' => 'extra 10% off', 'url' => '#'], ['title' => '$79 cashback', 'url' => '#'], ['title' => '80% off', 'url' => '#'],
        ],
        'secondary_banners' => [
            ['title' => 'Leather', 'subtitle' => 'new bag', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
            ['title' => 'Nike', 'subtitle' => 'breeze', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
            ['title' => 'Printing 3D', 'subtitle' => 'USB moon', 'button_text' => 'shop now', 'button_url' => '#', 'image_path' => null],
        ],
        'hot_deal' => [
            'title' => "today's hot deal", 'product_title' => 'Simply dummy text of the printing.',
            'description' => 'It is a long established fact that a reader. It is a long established fact that a reader.',
            'price' => '$45.00', 'old_price' => '$50.30',
            'images' => [['image_path' => null], ['image_path' => null], ['image_path' => null], ['image_path' => null]],
        ],
        'testimonials' => [
            ['name' => 'mark jecno', 'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.', 'image_path' => null],
            ['name' => 'mark jecno', 'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.', 'image_path' => null],
            ['name' => 'mark jecno', 'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.', 'image_path' => null],
        ],
        'instagram' => [
            ['url' => '#', 'image_path' => null], ['url' => '#', 'image_path' => null], ['url' => '#', 'image_path' => null], ['url' => '#', 'image_path' => null],
            ['url' => '#', 'image_path' => null], ['url' => '#', 'image_path' => null], ['url' => '#', 'image_path' => null], ['url' => '#', 'image_path' => null],
        ],
    ];
    $slides = old('hero_slides', $homeSection->hero_slides ?: $defaultSlides);
    $banners = old('collection_banners', $homeSection->collection_banners ?: $defaultBanners);
    $contentBlocks = array_replace_recursive($defaultContentBlocks, old('content_blocks', $homeSection->content_blocks ?: []));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Section - Bigdeal Admin</title>
    <link rel="icon" href="{{ $globalSiteInfo?->favicon_path ? asset('storage/' . $globalSiteInfo->favicon_path) : asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/admin.css">
    <style>
        .main-header-left .logo-wrapper img { max-width: 155px; max-height: 58px; object-fit: contain; }
        .preview-img { max-height: 70px; max-width: 180px; object-fit: contain; border: 1px solid #ddd; padding: 4px; margin-top: 8px; }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="page-main-header">
        <div class="main-header-left">
            <div class="logo-wrapper">
                <a href="{{ route('admin.dashboard') }}"><img class="blur-up lazyloaded" src="{{ $globalSiteInfo?->logo_path ? asset('storage/' . $globalSiteInfo->logo_path) : asset('assets/images/layout-2/logo/logo.png') }}" alt="{{ $globalSiteInfo?->site_name ?? 'Bigdeal' }}"></a>
            </div>
        </div>
        <div class="main-header-right row">
            <div class="mobile-sidebar">
                <div class="media-body text-right switch-sm">
                    <label class="switch"><input id="sidebar-toggle" type="checkbox" checked="checked"><span class="switch-state"></span></label>
                </div>
            </div>
            <div class="nav-right col">
                <ul class="nav-menus">
                    <li>
                        <form class="form-inline search-form">
                            <div class="form-group">
                                <input class="form-control-plaintext" type="search" placeholder="Search.."><span class="d-sm-none mobile-search"><i data-feather="search"></i></span>
                            </div>
                        </form>
                    </li>
                    <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                    <li class="onhover-dropdown">
                        <div class="media align-items-center"><img class="align-self-center pull-right img-50 rounded-circle blur-up lazyloaded" src="/assets/images/dashboard/man.png" alt="header-user"></div>
                        <ul class="profile-dropdown onhover-show-div p-20 profile-dropdown-hover">
                            <li><a href="{{ route('admin.site-info.edit') }}">Settings<span class="pull-right"><i data-feather="settings"></i></span></a></li>
                            <li><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="btn btn-link p-0 text-left w-100">Logout<span class="pull-right"><i data-feather="log-out"></i></span></button></form></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
            </div>
        </div>
    </div>

    <div class="page-body-wrapper">
        <div class="page-sidebar">
            <div class="sidebar custom-scrollbar">
                <div class="sidebar-user text-center">
                    <div><img class="img-60 rounded-circle lazyloaded blur-up" src="/assets/images/dashboard/man.png" alt=""></div>
                    <h6 class="mt-3 f-14">{{ auth('admin')->user()->name }}</h6>
                    <p>{{ auth('admin')->user()->is_super_admin ? 'Super Admin' : 'Admin' }}</p>
                </div>
                <ul class="sidebar-menu">
                    <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a></li>
                    <li>
                        <a class="sidebar-header" href="#"><i data-feather="box"></i><span>Catalog</span><i class="fa fa-angle-right pull-right"></i></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.catalog.index', 'categories') }}"><i class="fa fa-circle"></i>Categories</a></li>
                            <li><a href="{{ route('admin.catalog.index', 'sub-categories') }}"><i class="fa fa-circle"></i>Sub Categories</a></li>
                            <li><a href="{{ route('admin.catalog.index', 'child-categories') }}"><i class="fa fa-circle"></i>Child Categories</a></li>
                            <li><a href="{{ route('admin.catalog.index', 'brands') }}"><i class="fa fa-circle"></i>Brands</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="sidebar-header active" href="#"><i data-feather="settings"></i><span>Settings</span><i class="fa fa-angle-right pull-right"></i></a>
                        <ul class="sidebar-submenu menu-open" style="display:block;">
                            <li><a href="{{ route('admin.site-info.edit') }}"><i class="fa fa-circle"></i>Site Info</a></li>
                            <li><a class="active" href="{{ route('admin.home-section.edit') }}"><i class="fa fa-circle"></i>Home Section</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="page-header-left">
                                <h3>Home Section<small>Bigdeal Admin Panel</small></h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ol class="breadcrumb pull-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item">Settings</li>
                                <li class="breadcrumb-item active">Home Section</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('admin.home-section.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card tab2-card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-material" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#slides" role="tab"><i data-feather="image" class="mr-2"></i>Hero Slides</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#banners" role="tab"><i data-feather="columns" class="mr-2"></i>Banners</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#content-blocks" role="tab"><i data-feather="edit-3" class="mr-2"></i>Content Blocks</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="slides" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="f-w-600 mb-0">Hero Slides</h5>
                                        <button type="button" class="btn btn-primary" id="add-hero-slide">Add New Slide</button>
                                    </div>
                                    <div id="hero-slides-wrapper">
                                        @foreach ($slides as $index => $slide)
                                            <div class="border rounded p-3 mb-4 hero-slide-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="f-w-600 mb-0">Hero Slide {{ $index + 1 }}</h5>
                                                    <button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#hero-slide-{{ $index }}">Edit</button>
                                                </div>
                                                <div class="collapse {{ $index === 0 ? 'show' : '' }} mt-3" id="hero-slide-{{ $index }}">
                                                    <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Title</label>
                                                    <input class="form-control" name="hero_slides[{{ $index }}][title]" value="{{ $slide['title'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Title Highlight</label>
                                                    <input class="form-control" name="hero_slides[{{ $index }}][title_highlight]" value="{{ $slide['title_highlight'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Subtitle</label>
                                                    <input class="form-control" name="hero_slides[{{ $index }}][subtitle]" value="{{ $slide['subtitle'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Heading</label>
                                                    <input class="form-control" name="hero_slides[{{ $index }}][heading]" value="{{ $slide['heading'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Button Text</label>
                                                    <input class="form-control" name="hero_slides[{{ $index }}][button_text]" value="{{ $slide['button_text'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Button URL</label>
                                                    <input class="form-control" name="hero_slides[{{ $index }}][button_url]" value="{{ $slide['button_url'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Left Image</label>
                                                    <input class="form-control" type="file" name="hero_slides[{{ $index }}][image_one]" accept="image/*">
                                                    @if (! empty($slide['image_one_path']))
                                                        <img class="preview-img" src="{{ asset('storage/' . $slide['image_one_path']) }}" alt="">
                                                    @endif
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Right Image</label>
                                                    <input class="form-control" type="file" name="hero_slides[{{ $index }}][image_two]" accept="image/*">
                                                    @if (! empty($slide['image_two_path']))
                                                        <img class="preview-img" src="{{ asset('storage/' . $slide['image_two_path']) }}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                                </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="banners" role="tabpanel">
                                    @foreach ($banners as $index => $banner)
                                        <div class="border rounded p-3 mb-4">
                                            <h5 class="f-w-600">Collection Banner {{ $index + 1 }}</h5>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Title</label>
                                                    <input class="form-control" name="collection_banners[{{ $index }}][title]" value="{{ $banner['title'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Subtitle</label>
                                                    <input class="form-control" name="collection_banners[{{ $index }}][subtitle]" value="{{ $banner['subtitle'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Button Text</label>
                                                    <input class="form-control" name="collection_banners[{{ $index }}][button_text]" value="{{ $banner['button_text'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Button URL</label>
                                                    <input class="form-control" name="collection_banners[{{ $index }}][button_url]" value="{{ $banner['button_url'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Image</label>
                                                    <input class="form-control" type="file" name="collection_banners[{{ $index }}][image]" accept="image/*">
                                                    @if (! empty($banner['image_path']))
                                                        <img class="preview-img" src="{{ asset('storage/' . $banner['image_path']) }}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="tab-pane fade" id="content-blocks" role="tabpanel">
                                    <h5 class="f-w-600">Discount Banner</h5>
                                    <div class="row">
                                        @foreach(['subtitle' => 'Subtitle', 'title' => 'Title', 'highlight' => 'Highlight', 'description' => 'Rounded Text'] as $field => $label)
                                            <div class="col-md-6 form-group">
                                                <label>{{ $label }}</label>
                                                <input class="form-control" name="content_blocks[discount][{{ $field }}]" value="{{ $contentBlocks['discount'][$field] ?? '' }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    <h5 class="f-w-600 mt-4">Wide Fashion Banner</h5>
                                    <div class="row">
                                        @foreach(['subtitle' => 'Subtitle', 'title' => 'Title', 'highlight' => 'Highlight', 'button_text' => 'Button Text', 'button_url' => 'Button URL'] as $field => $label)
                                            <div class="col-md-4 form-group">
                                                <label>{{ $label }}</label>
                                                <input class="form-control" name="content_blocks[wide_banner][{{ $field }}]" value="{{ $contentBlocks['wide_banner'][$field] ?? '' }}">
                                            </div>
                                        @endforeach
                                        <div class="col-md-4 form-group">
                                            <label>Image</label>
                                            <input class="form-control" type="file" name="content_blocks[wide_banner][image]" accept="image/*">
                                            @if(!empty($contentBlocks['wide_banner']['image_path']))
                                                <img class="preview-img" src="{{ asset('storage/' . $contentBlocks['wide_banner']['image_path']) }}" alt="">
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="f-w-600 mt-4">Deal Banner</h5>
                                    <div class="row">
                                        @foreach(['subtitle' => 'Subtitle', 'title' => 'Title', 'button_text' => 'Button Text', 'button_url' => 'Button URL'] as $field => $label)
                                            <div class="col-md-6 form-group">
                                                <label>{{ $label }}</label>
                                                <input class="form-control" name="content_blocks[deal_banner][{{ $field }}]" value="{{ $contentBlocks['deal_banner'][$field] ?? '' }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    <h5 class="f-w-600 mt-4">Coupon Tiles</h5>
                                    <div class="row">
                                        @foreach($contentBlocks['coupon_tiles'] as $index => $tile)
                                            <div class="col-md-4 form-group">
                                                <label>Tile {{ $index + 1 }}</label>
                                                <input class="form-control mb-2" name="content_blocks[coupon_tiles][{{ $index }}][title]" value="{{ $tile['title'] ?? '' }}">
                                                <input class="form-control" name="content_blocks[coupon_tiles][{{ $index }}][url]" value="{{ $tile['url'] ?? '#' }}" placeholder="URL">
                                            </div>
                                        @endforeach
                                    </div>

                                    <h5 class="f-w-600 mt-4">Secondary Banners</h5>
                                    @foreach($contentBlocks['secondary_banners'] as $index => $banner)
                                        <div class="border rounded p-3 mb-3">
                                            <h6>Banner {{ $index + 1 }}</h6>
                                            <div class="row">
                                                @foreach(['title' => 'Title', 'subtitle' => 'Subtitle', 'button_text' => 'Button Text', 'button_url' => 'Button URL'] as $field => $label)
                                                    <div class="col-md-3 form-group">
                                                        <label>{{ $label }}</label>
                                                        <input class="form-control" name="content_blocks[secondary_banners][{{ $index }}][{{ $field }}]" value="{{ $banner[$field] ?? '' }}">
                                                    </div>
                                                @endforeach
                                                <div class="col-md-4 form-group">
                                                    <label>Image</label>
                                                    <input class="form-control" type="file" name="content_blocks[secondary_banners][{{ $index }}][image]" accept="image/*">
                                                    @if(!empty($banner['image_path']))
                                                        <img class="preview-img" src="{{ asset('storage/' . $banner['image_path']) }}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <h5 class="f-w-600 mt-4">Hot Deal</h5>
                                    <div class="row">
                                        @foreach(['title' => 'Section Title', 'product_title' => 'Product Title', 'price' => 'Price', 'old_price' => 'Old Price'] as $field => $label)
                                            <div class="col-md-6 form-group">
                                                <label>{{ $label }}</label>
                                                <input class="form-control" name="content_blocks[hot_deal][{{ $field }}]" value="{{ $contentBlocks['hot_deal'][$field] ?? '' }}">
                                            </div>
                                        @endforeach
                                        <div class="col-md-12 form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="content_blocks[hot_deal][description]" rows="3">{{ $contentBlocks['hot_deal']['description'] ?? '' }}</textarea>
                                        </div>
                                        @foreach($contentBlocks['hot_deal']['images'] as $index => $image)
                                            <div class="col-md-3 form-group">
                                                <label>Hot Deal Image {{ $index + 1 }}</label>
                                                <input class="form-control" type="file" name="content_blocks[hot_deal][images][{{ $index }}][image]" accept="image/*">
                                                @if(!empty($image['image_path']))
                                                    <img class="preview-img" src="{{ asset('storage/' . $image['image_path']) }}" alt="">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <h5 class="f-w-600 mt-4">Testimonials</h5>
                                    @foreach($contentBlocks['testimonials'] as $index => $testimonial)
                                        <div class="border rounded p-3 mb-3">
                                            <h6>Testimonial {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-4 form-group"><label>Name</label><input class="form-control" name="content_blocks[testimonials][{{ $index }}][name]" value="{{ $testimonial['name'] ?? '' }}"></div>
                                                <div class="col-md-8 form-group"><label>Description</label><textarea class="form-control" name="content_blocks[testimonials][{{ $index }}][description]" rows="2">{{ $testimonial['description'] ?? '' }}</textarea></div>
                                                <div class="col-md-4 form-group">
                                                    <label>Image</label>
                                                    <input class="form-control" type="file" name="content_blocks[testimonials][{{ $index }}][image]" accept="image/*">
                                                    @if(!empty($testimonial['image_path']))
                                                        <img class="preview-img" src="{{ asset('storage/' . $testimonial['image_path']) }}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <h5 class="f-w-600 mt-4">Instagram Images</h5>
                                    <div class="row">
                                        @foreach($contentBlocks['instagram'] as $index => $instagram)
                                            <div class="col-md-3 form-group">
                                                <label>Instagram {{ $index + 1 }}</label>
                                                <input class="form-control mb-2" name="content_blocks[instagram][{{ $index }}][url]" value="{{ $instagram['url'] ?? '#' }}" placeholder="URL">
                                                <input class="form-control" type="file" name="content_blocks[instagram][{{ $index }}][image]" accept="image/*">
                                                @if(!empty($instagram['image_path']))
                                                    <img class="preview-img" src="{{ asset('storage/' . $instagram['image_path']) }}" alt="">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 footer-copyright"><p class="mb-0">Copyright {{ date('Y') }} © Bigdeal All rights reserved.</p></div>
                    <div class="col-md-6"><p class="pull-right mb-0">Hand crafted & made with<i class="fa fa-heart"></i></p></div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="/assets/js/icons/feather-icon/feather-icon.js"></script>
<script src="/assets/js/sidebar-menu.js"></script>
<script src="/assets/js/admin-customizer.js"></script>
<script src="/assets/js/lazysizes.min.js"></script>
<script src="/assets/js/admin-script.js"></script>
<script>
    (function () {
        var slideIndex = {{ count($slides) }};
        var wrapper = document.getElementById('hero-slides-wrapper');
        var addButton = document.getElementById('add-hero-slide');

        if (!wrapper || !addButton) {
            return;
        }

        addButton.addEventListener('click', function () {
            var collapseId = 'hero-slide-' + slideIndex;
            var slideNumber = slideIndex + 1;
            var html = ''
                + '<div class="border rounded p-3 mb-4 hero-slide-item">'
                + '<div class="d-flex justify-content-between align-items-center">'
                + '<h5 class="f-w-600 mb-0">Hero Slide ' + slideNumber + '</h5>'
                + '<button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#' + collapseId + '">Edit</button>'
                + '</div>'
                + '<div class="collapse show mt-3" id="' + collapseId + '">'
                + '<div class="row">'
                + '<div class="col-md-6 form-group"><label>Title</label><input class="form-control" name="hero_slides[' + slideIndex + '][title]"></div>'
                + '<div class="col-md-6 form-group"><label>Title Highlight</label><input class="form-control" name="hero_slides[' + slideIndex + '][title_highlight]"></div>'
                + '<div class="col-md-6 form-group"><label>Subtitle</label><input class="form-control" name="hero_slides[' + slideIndex + '][subtitle]"></div>'
                + '<div class="col-md-6 form-group"><label>Heading</label><input class="form-control" name="hero_slides[' + slideIndex + '][heading]"></div>'
                + '<div class="col-md-6 form-group"><label>Button Text</label><input class="form-control" name="hero_slides[' + slideIndex + '][button_text]" value="Shop Now"></div>'
                + '<div class="col-md-6 form-group"><label>Button URL</label><input class="form-control" name="hero_slides[' + slideIndex + '][button_url]" value="#"></div>'
                + '<div class="col-md-6 form-group"><label>Left Image</label><input class="form-control" type="file" name="hero_slides[' + slideIndex + '][image_one]" accept="image/*"></div>'
                + '<div class="col-md-6 form-group"><label>Right Image</label><input class="form-control" type="file" name="hero_slides[' + slideIndex + '][image_two]" accept="image/*"></div>'
                + '</div></div></div>';

            wrapper.insertAdjacentHTML('beforeend', html);
            slideIndex++;
        });
    })();
</script>
</body>
</html>
