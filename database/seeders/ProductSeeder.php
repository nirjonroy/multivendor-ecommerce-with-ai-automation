<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $category = Category::query()->first();
        $brand = Brand::query()->first();

        if (! $category) {
            return;
        }

        $products = [
            ['Wireless Smart Camera', 56.21, 24.05, true],
            ['Premium Travel Bag', 89.00, 59.00, false],
            ['Kitchen Grocery Pack', 40.00, 32.50, true],
            ['Sports Training Kit', 75.00, 55.00, false],
            ['Cardigan Classic Fit', 64.00, 48.00, true],
            ['Portable Tool Set', 39.99, 29.99, false],
        ];

        foreach ($products as $index => [$name, $price, $offerPrice, $sale]) {
            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sku' => 'SKU-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'category_id' => $category->id,
                    'brand_id' => $brand?->id,
                    'owner_type' => 'admin',
                    'vendor_id' => null,
                    'price' => $price,
                    'offer_price' => $offerPrice,
                    'stock_quantity' => $index === count($products) - 1 ? 0 : 50 + $index,
                    'short_description' => 'Reader will be distracted.',
                    'long_description' => 'A quality marketplace product with admin managed catalog data.',
                    'sizes' => ['free', '28'],
                    'colors' => ['default', 'Merun'],
                    'size_prices' => [
                        ['size' => 'free', 'price' => $offerPrice],
                        ['size' => '28', 'price' => $offerPrice + 5],
                    ],
                    'color_images' => [
                        ['color' => 'default', 'image_path' => null],
                        ['color' => 'Merun', 'image_path' => null],
                    ],
                    'variation_stocks' => [
                        ['variation' => 'free_default', 'quantity' => 10],
                        ['variation' => '28_Merun', 'quantity' => 15],
                    ],
                    'has_variation_stock' => true,
                    'status' => 'published',
                    'is_featured' => $index < 3,
                    'is_new' => true,
                    'is_on_sale' => $sale,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
