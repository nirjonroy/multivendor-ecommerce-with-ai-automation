<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function store(Request $request, Product $product)
    {
        abort_if($product->status !== 'published' || $product->approval_status !== 'approved', 404);

        $request->user()->wishlistProducts()->syncWithoutDetaching([$product->id]);

        return back()->with('status', 'Product added to wishlist.');
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()->wishlistProducts()->detach($product->id);

        return back()->with('status', 'Product removed from wishlist.');
    }
}
