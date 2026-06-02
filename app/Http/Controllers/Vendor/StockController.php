<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $vendor = auth('vendor')->user();

        $products = Product::query()
            ->with(['category', 'brand'])
            ->where('owner_type', 'vendor')
            ->where('vendor_id', $vendor->id)
            ->orderBy('stock_quantity')
            ->paginate(12);

        return view('vendor.stock.index', [
            'products' => $products,
            'totalStock' => Product::where('owner_type', 'vendor')->where('vendor_id', $vendor->id)->sum('stock_quantity'),
            'lowStockCount' => Product::where('owner_type', 'vendor')->where('vendor_id', $vendor->id)->whereBetween('stock_quantity', [1, 5])->count(),
            'outOfStockCount' => Product::where('owner_type', 'vendor')->where('vendor_id', $vendor->id)->where('stock_quantity', 0)->count(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->owner_type === 'vendor' && (int) $product->vendor_id === (int) auth('vendor')->id(), 404);

        $data = $request->validate([
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'variation_stocks' => ['nullable', 'array'],
            'variation_stocks.*.variation' => ['nullable', 'string', 'max:255'],
            'variation_stocks.*.quantity' => ['nullable', 'integer', 'min:0'],
        ]);

        $variationStocks = collect($data['variation_stocks'] ?? [])
            ->filter(fn ($row) => ! empty($row['variation']))
            ->map(fn ($row) => [
                'variation' => $row['variation'],
                'quantity' => (int) ($row['quantity'] ?? 0),
            ])
            ->values()
            ->all();

        $product->update([
            'stock_quantity' => $data['stock_quantity'],
            'variation_stocks' => $variationStocks ?: $product->variation_stocks,
        ]);

        return redirect()->route('vendor.stock.index')->with('status', 'Stock updated for ' . $product->name . '.');
    }
}
