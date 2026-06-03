<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->where('status', 'published')
            ->where('approval_status', 'approved')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->input('q');
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->input('category_id')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->status === 'published' && $product->approval_status === 'approved', 404);

        $relatedProducts = Product::query()
            ->whereKeyNot($product->id)
            ->where('status', 'published')
            ->where('approval_status', 'approved')
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
