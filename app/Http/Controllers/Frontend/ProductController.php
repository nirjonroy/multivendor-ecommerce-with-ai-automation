<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
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
