<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Western Ware',
            'TV, Appliances',
            'Pets Products',
            'Car, Motorbike',
            'Industrial Products',
            'Beauty, Health Products',
            'Grocery Products',
            'Sports',
            'Bags, Luggage',
            'Movies, Music',
            'Video Games',
            'Toys, Baby Products',
            'Tools',
            'Camera',
            'Cardigans',
        ];

        foreach ($categories as $index => $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $name . ' category',
                    'owner_type' => 'admin',
                    'vendor_id' => null,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $brands = [
            'Aerie',
            'Baci Lingrie',
            'Gerbe',
            'Jolidon',
            'Wonderbra',
            'Ultimo',
            'Vassarette',
            'Oysho',
        ];

        foreach ($brands as $index => $name) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $name . ' brand',
                    'owner_type' => 'admin',
                    'vendor_id' => null,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
