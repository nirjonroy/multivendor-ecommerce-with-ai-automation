<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Support\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function placeOrder(Request $request)
    {
        $cartItems = $this->cartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:sslcommerz_demo,cash_on_delivery'],
        ]);

        $subtotal = $cartItems->sum('subtotal');
        $shippingAmount = 0;
        $total = $subtotal + $shippingAmount;
        $transactionId = $data['payment_method'] === 'sslcommerz_demo'
            ? 'SSLCZ-DEMO-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6))
            : 'COD-DEMO-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

        $order = DB::transaction(function () use ($request, $cartItems, $data, $transactionId, $subtotal, $shippingAmount, $total) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
                'transaction_id' => $transactionId,
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'sslcommerz_demo' ? 'demo_paid' : 'pending',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_amount' => $shippingAmount,
                'total' => $total,
                'billing_name' => $data['name'],
                'billing_email' => $data['email'],
                'billing_phone' => $data['phone'],
                'billing_address' => $data['address'],
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'vendor_id' => $item['product']->vendor_id,
                    'product_name' => $item['product']->name,
                    'sku' => $item['product']->sku,
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $request->user()->update([
                'phone' => $data['phone'],
                'billing_address' => $data['address'],
                'shipping_address' => $request->user()->shipping_address ?: $data['address'],
            ]);

            return $order;
        });

        session()->forget('cart');

        return redirect()
            ->route('dashboard.section', 'orders')
            ->with('status', 'Demo order placed successfully. Order: ' . $order->order_number . '. Transaction ID: ' . $transactionId);
    }

    private function addProduct(Request $request, Product $product): void
    {
        abort_if($product->stock_quantity <= 0 || $product->status !== 'published' || $product->approval_status !== 'approved', 404);

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
