<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'How To Choose Reliable Marketplace Vendors',
                'excerpt' => 'A practical checklist for buying from multivendor stores with confidence.',
                'content' => '<p>Successful marketplace shopping starts with trust. Check seller ratings, product descriptions, return policies, and customer communication before placing an order.</p><p>Reliable vendors keep stock updated, respond quickly, and provide clear product information. These details help customers make confident buying decisions.</p>',
            ],
            [
                'title' => 'Smart Stock Management For Online Shops',
                'excerpt' => 'Simple stock practices that reduce cancelled orders and improve customer satisfaction.',
                'content' => '<p>Inventory accuracy matters in ecommerce. Vendors should review fast-moving products daily and update low-stock products before customers place orders.</p><p>Variation stock is especially important for products with size and color options. Keeping each variation updated prevents overselling.</p>',
            ],
            [
                'title' => 'Why Product Images Improve Conversion',
                'excerpt' => 'Better product photos help customers inspect details and buy faster.',
                'content' => '<p>Product images are one of the strongest signals in ecommerce. Clear thumbnails, multiple gallery photos, and honest visuals reduce uncertainty.</p><p>Customers compare products quickly, so accurate images and readable product descriptions can directly improve conversion.</p>',
            ],
        ];

        foreach ($posts as $post) {
            Blog::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                array_merge($post, [
                    'author_name' => 'Marketplace Team',
                    'is_published' => true,
                    'published_at' => now(),
                ])
            );
        }
    }
}
