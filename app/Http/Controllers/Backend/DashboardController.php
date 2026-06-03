<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'products_total' => Product::count(),
            'products_published' => Product::where('status', 'published')->count(),
            'products_pending' => Product::where('owner_type', 'vendor')->where('approval_status', 'pending')->count(),
            'orders_total' => Order::count(),
            'orders_this_month' => Order::where('created_at', '>=', $monthStart)->count(),
            'sales_this_month' => Order::where('created_at', '>=', $monthStart)->sum('total'),
            'sales_total' => Order::sum('total'),
            'vendors_total' => Vendor::count(),
            'vendors_pending' => Vendor::where('status', 'pending')->count(),
            'vendors_new_this_month' => Vendor::where('created_at', '>=', $monthStart)->count(),
            'customers_total' => User::count(),
            'blogs_total' => Blog::count(),
            'blogs_published' => Blog::where('is_published', true)->count(),
        ];

        $latestOrders = Order::with('user')->latest()->take(8)->get();

        $pendingVendorProducts = Product::with(['vendor', 'category', 'brand'])
            ->where('owner_type', 'vendor')
            ->where('approval_status', 'pending')
            ->latest()
            ->take(8)
            ->get();

        $pendingVendors = Vendor::where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $lowStockProducts = Product::with(['vendor', 'category'])
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->take(8)
            ->get();

        $topProducts = OrderItem::query()
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as sold_quantity'), DB::raw('SUM(subtotal) as sales_total'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('sold_quantity')
            ->take(8)
            ->get();

        return view('backend.dashboard.index', compact(
            'stats',
            'latestOrders',
            'pendingVendorProducts',
            'pendingVendors',
            'lowStockProducts',
            'topProducts'
        ));
    }
}
