<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $vendor = auth('vendor')->user();
        $type = $request->input('type', 'sales');
        $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();

        $items = OrderItem::query()
            ->with(['order.user', 'product'])
            ->where('vendor_id', $vendor->id)
            ->whereHas('order', fn ($query) => $query->whereBetween('created_at', [$from, $to]));

        $allItems = (clone $items)->get();
        $products = Product::query()
            ->with(['category', 'brand'])
            ->where('owner_type', 'vendor')
            ->where('vendor_id', $vendor->id)
            ->get();

        $summary = [
            'sales' => $allItems->sum('subtotal'),
            'orders' => $allItems->pluck('order_id')->unique()->count(),
            'units' => $allItems->sum('quantity'),
            'products' => $products->count(),
            'stock' => $products->sum('stock_quantity'),
            'low_stock' => $products->whereBetween('stock_quantity', [1, 5])->count(),
            'out_of_stock' => $products->where('stock_quantity', 0)->count(),
        ];

        $dailySales = (clone $items)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('DATE(orders.created_at) as report_date, SUM(order_items.subtotal) as sales_total, SUM(order_items.quantity) as units_total, COUNT(DISTINCT orders.id) as orders_total')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();

        $topProducts = (clone $items)
            ->selectRaw('product_id, product_name, sku, SUM(quantity) as units_total, SUM(subtotal) as sales_total')
            ->groupBy('product_id', 'product_name', 'sku')
            ->orderByDesc('sales_total')
            ->take(10)
            ->get();

        $statusBreakdown = (clone $items)
            ->selectRaw('status, COUNT(*) as items_count, SUM(quantity) as units_total, SUM(subtotal) as sales_total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $paymentBreakdown = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('orders.payment_method, orders.payment_status, COUNT(DISTINCT orders.id) as orders_total, SUM(order_items.subtotal) as sales_total')
            ->groupBy('orders.payment_method', 'orders.payment_status')
            ->orderBy('orders.payment_method')
            ->get();

        $stockProducts = $products
            ->sortBy('stock_quantity')
            ->values();

        return view('vendor.reports.index', compact(
            'type',
            'from',
            'to',
            'summary',
            'dailySales',
            'topProducts',
            'statusBreakdown',
            'paymentBreakdown',
            'stockProducts'
        ));
    }
}
