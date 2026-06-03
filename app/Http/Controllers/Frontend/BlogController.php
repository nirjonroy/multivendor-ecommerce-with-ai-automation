<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::query()
            ->where('is_published', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->input('q');
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.blog.index', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        $recentBlogs = Blog::query()
            ->where('is_published', true)
            ->whereKeyNot($blog->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('frontend.blog.show', compact('blog', 'recentBlogs'));
    }
}
