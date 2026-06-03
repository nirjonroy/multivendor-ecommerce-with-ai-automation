<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => $blog->title])
    <style>
        .blog-detail{background:#fff;border:1px solid #e6e6e6;padding:30px}
        .blog-detail-img{width:100%;max-height:440px;object-fit:cover;background:#f1f1f1;margin-bottom:24px}
        .blog-content{line-height:1.8;color:#555}
        .recent-blog-card{background:#fff;border:1px solid #e6e6e6;padding:16px;margin-bottom:14px}
        .blog-sidebar{background:#fff;border:1px solid #e6e6e6;padding:20px}
    </style>
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>{{ $blog->title }}</h2>
                <ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a href="{{ route('blog.index') }}">blog</a></li></ul>
            </div>
        </div>
    </div>
</section>
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <div class="row">
            <div class="col-lg-8">
                <article class="blog-detail">
                    <img class="blog-detail-img" src="{{ $blog->image_path ? asset('storage/'.$blog->image_path) : asset('assets/images/blog/1.jpg') }}" alt="{{ $blog->title }}">
                    <p>{{ $blog->published_at?->format('d M Y') }} / {{ $blog->author_name }}</p>
                    <h2 class="mb-3">{{ $blog->title }}</h2>
                    <div class="blog-content">{!! $blog->content !!}</div>
                </article>
            </div>
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <h4 class="mb-3">Recent Posts</h4>
                    @forelse($recentBlogs as $recentBlog)
                        <div class="recent-blog-card">
                            <a href="{{ route('blog.show', $recentBlog) }}"><strong>{{ $recentBlog->title }}</strong></a>
                            <p class="mb-0">{{ $recentBlog->published_at?->format('d M Y') }}</p>
                        </div>
                    @empty
                        <p>No recent posts.</p>
                    @endforelse
                </div>
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
