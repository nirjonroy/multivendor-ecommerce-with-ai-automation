<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Blog'])
    <style>
        .blog-card{background:#fff;border:1px solid #e6e6e6;margin-bottom:24px;height:100%}
        .blog-card-img{width:100%;aspect-ratio:16/10;object-fit:cover;background:#f1f1f1}
        .blog-card-body{padding:20px}
        .blog-card-body h4{line-height:1.35;margin-bottom:10px}
        .blog-search{background:#fff;border:1px solid #e6e6e6;padding:16px;margin-bottom:24px}
    </style>
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>Blog</h2>
                <ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a>blog</a></li></ul>
            </div>
        </div>
    </div>
</section>
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <form class="blog-search" method="GET" action="{{ route('blog.index') }}">
            <div class="row align-items-end">
                <div class="col-md-10 form-group mb-md-0">
                    <label>Search Blog</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search blog posts">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-normal btn-block" type="submit">Search</button>
                </div>
            </div>
        </form>
        <div class="row">
            @forelse($blogs as $blog)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="blog-card">
                        <a href="{{ route('blog.show', $blog) }}">
                            <img class="blog-card-img" src="{{ \App\Support\PublicMedia::url($blog->image_path, 'assets/images/blog/1.jpg') }}" alt="{{ $blog->title }}">
                        </a>
                        <div class="blog-card-body">
                            <p class="mb-2">{{ $blog->published_at?->format('d M Y') }} / {{ $blog->author_name }}</p>
                            <a href="{{ route('blog.show', $blog) }}"><h4>{{ $blog->title }}</h4></a>
                            <p>{{ $blog->excerpt }}</p>
                            <a href="{{ route('blog.show', $blog) }}" class="btn btn-normal mt-3">Read More</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12"><div class="bg-white p-5 text-center"><h4>No blog posts found.</h4></div></div>
            @endforelse
        </div>
        {{ $blogs->links() }}
    </div>
</section>
@include('frontend.partials.footer')
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>
