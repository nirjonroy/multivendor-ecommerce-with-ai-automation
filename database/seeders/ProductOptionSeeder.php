<?php

namespace Database\Seeders;

use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductOptionSeeder extends Seeder
{
    public function run()
    {
        $sizes = ['free', '28', '30', '32', '34', '36', '38', '40', 'S', 'M', 'L', 'XL', 'XXL', '42', '44'];
        foreach ($sizes as $index => $name) {
            ProductSize::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'owner_type' => 'admin', 'is_active' => true, 'sort_order' => $index + 1]);
        }

        $colors = [
            'default' => null, 'Red' => '#ff0000', 'Blue' => '#0000ff', 'Yellow' => '#ffff00',
            'Merun' => '#800000', 'Ash' => '#b2beb5', 'pink' => '#ffc0cb', 'Navy Blue' => '#000080',
            'Green' => '#008000', 'Grey' => '#808080', 'Flouroscent yellow' => '#ccff00',
            'Brown Sky' => '#8b6f47', 'Black Red' => '#1b0000', 'Black Flourscent Yellow' => '#0b0b00',
        ];
        $index = 1;
        foreach ($colors as $name => $hex) {
            ProductColor::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'hex_code' => $hex, 'owner_type' => 'admin', 'is_active' => true, 'sort_order' => $index++]);
        }
    }
}
