<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'sales');
        $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();

        $orders = Order::query()->whereBetween('created_at', [$from, $to]);
        $items = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$from, $to]);

        $summary = [
            'sales' => (clone $orders)->sum('total'),
            'orders' => (clone $orders)->count(),
            'units' => (clone $items)->sum('order_items.quantity'),
            'customers' => User::count(),
            'vendors' => Vendor::count(),
            'products' => Product::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_vendor_products' => Product::where('owner_type', 'vendor')->where('approval_status', 'pending')->count(),
            'low_stock' => Product::whereBetween('stock_quantity', [1, 5])->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
        ];

        $dailySales = (clone $orders)
            ->selectRaw('DATE(created_at) as report_date, COUNT(*) as orders_total, SUM(subtotal) as subtotal_total, SUM(shipping_amount) as shipping_total, SUM(total) as sales_total')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();

        $orderStatusBreakdown = (clone $orders)
            ->selectRaw('status, COUNT(*) as orders_total, SUM(total) as sales_total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $paymentBreakdown = (clone $orders)
            ->selectRaw('payment_method, payment_status, COUNT(*) as orders_total, SUM(total) as sales_total')
            ->groupBy('payment_method', 'payment_status')
            ->orderBy('payment_method')
            ->get();

        $topProducts = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('vendors', 'order_items.vendor_id', '=', 'vendors.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('order_items.product_id, order_items.product_name, order_items.sku, products.owner_type, vendors.shop_name, SUM(order_items.quantity) as units_total, SUM(order_items.subtotal) as sales_total')
            ->groupBy('order_items.product_id', 'order_items.product_name', 'order_items.sku', 'products.owner_type', 'vendors.shop_name')
            ->orderByDesc('sales_total')
            ->take(20)
            ->get();

        $stockProducts = Product::with(['category', 'brand', 'vendor'])
            ->orderBy('stock_quantity')
            ->take(50)
            ->get();

        $vendorPerformance = Vendor::query()
            ->leftJoin('order_items', 'vendors.id', '=', 'order_items.vendor_id')
            ->leftJoin('orders', function ($join) use ($from, $to) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$from, $to]);
            })
            ->selectRaw('vendors.id, vendors.name, vendors.shop_name, vendors.email, vendors.status, vendors.kyc_status, COUNT(DISTINCT orders.id) as orders_total, COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.quantity ELSE 0 END), 0) as units_total, COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.subtotal ELSE 0 END), 0) as sales_total')
            ->groupBy('vendors.id', 'vendors.name', 'vendors.shop_name', 'vendors.email', 'vendors.status', 'vendors.kyc_status')
            ->orderByDesc('sales_total')
            ->take(30)
            ->get();

        $customerPerformance = User::query()
            ->leftJoin('orders', function ($join) use ($from, $to) {
                $join->on('users.id', '=', 'orders.user_id')
                    ->whereBetween('orders.created_at', [$from, $to]);
            })
            ->selectRaw('users.id, users.name, users.email, users.phone, COUNT(orders.id) as orders_total, COALESCE(SUM(orders.total), 0) as sales_total, MAX(orders.created_at) as last_order_at')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone')
            ->orderByDesc('sales_total')
            ->take(30)
            ->get();

        $adminProductSales = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNull('order_items.vendor_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('order_items.product_id, order_items.product_name, order_items.sku, SUM(order_items.quantity) as units_total, SUM(order_items.subtotal) as sales_total')
            ->groupBy('order_items.product_id', 'order_items.product_name', 'order_items.sku')
            ->orderByDesc('sales_total')
            ->take(20)
            ->get();

        return view('backend.reports.index', compact(
            'type',
            'from',
            'to',
            'summary',
            'dailySales',
            'orderStatusBreakdown',
            'paymentBreakdown',
            'topProducts',
            'stockProducts',
            'vendorPerformance',
            'customerPerformance',
            'adminProductSales'
        ));
    }
}
