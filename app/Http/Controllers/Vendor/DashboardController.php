<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $vendor = auth('vendor')->user();
        $products = Product::query()
            ->where('owner_type', 'vendor')
            ->where('vendor_id', $vendor->id);
        $orders = Order::query()
            ->whereHas('items', fn ($query) => $query->where('vendor_id', $vendor->id));

        return view('vendor.dashboard.index', [
            'vendor' => $vendor,
            'totalProducts' => (clone $products)->count(),
            'publishedProducts' => (clone $products)->where('status', 'published')->count(),
            'draftProducts' => (clone $products)->where('status', 'draft')->count(),
            'stockQuantity' => (clone $products)->sum('stock_quantity'),
            'latestProducts' => (clone $products)->latest()->take(8)->get(),
            'totalOrders' => (clone $orders)->count(),
            'latestOrders' => (clone $orders)->with(['items' => fn ($query) => $query->where('vendor_id', $vendor->id)])->latest()->take(5)->get(),
        ]);
    }
}
