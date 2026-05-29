<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Currency;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.cart.index', [
            'cartItems' => $this->cartItems(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $this->addProduct($request, $product);

        return redirect()->route('cart.index')->with('status', 'Product added to cart.');
    }

    public function buyNow(Request $request, Product $product)
    {
        session()->forget('cart');
        $this->addProduct($request, $product);

        return redirect()->route('checkout.index');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max(1, $product->stock_quantity)],
        ]);

        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $data['quantity'];
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            $cartItems = $this->cartItems();
            $item = $cartItems->first(fn ($cartItem) => $cartItem['product']->is($product));

            return response()->json([
                'message' => 'Cart updated.',
                'quantity' => $item['quantity'] ?? $data['quantity'],
                'cart_quantity' => collect(session('cart', []))->sum('quantity'),
                'subtotal' => Currency::format($item['subtotal'] ?? 0),
                'total' => Currency::format($cartItems->sum('subtotal')),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated.');
    }

    public function destroy(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Product removed from cart.');
    }

    public function checkout()
    {
        return view('frontend.checkout.index', [
            'cartItems' => $this->cartItems(),
        ]);
    }

    private function addProduct(Request $request, Product $product): void
    {
        abort_if($product->stock_quantity <= 0 || $product->status !== 'published', 404);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $product->stock_quantity],
            'size' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:100'],
        ]);

        $cart = session('cart', []);
        $existing = $cart[$product->id]['quantity'] ?? 0;
        $quantity = min($product->stock_quantity, $existing + $data['quantity']);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'size' => $data['size'] ?? null,
            'color' => $data['color'] ?? null,
        ];

        session(['cart' => $cart]);
    }

    private function cartItems()
    {
        $cart = session('cart', []);
        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function ($item) use ($products) {
                $product = $products->get($item['product_id']);
                if (! $product) {
                    return null;
                }

                $price = $product->offer_price ?: $product->price;

                return [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'price' => $price,
                    'subtotal' => $price * $item['quantity'],
                ];
            })
            ->filter()
            ->values();
    }
}
