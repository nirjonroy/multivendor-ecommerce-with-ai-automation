<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run()
    {
        HomeSection::firstOrCreate(
            ['id' => 1],
            [
                'hero_slides' => [
                    [
                        'title' => 'mi',
                        'title_highlight' => 'Mobile',
                        'subtitle' => 'fast and light',
                        'heading' => 'Pixel Perfect Deal Camera',
                        'button_text' => 'Shop Now',
                        'button_url' => '#',
                        'image_one_path' => null,
                        'image_two_path' => null,
                    ],
                    [
                        'title' => 'big',
                        'title_highlight' => 'Sale',
                        'subtitle' => 'now start at $99',
                        'heading' => '50% off',
                        'button_text' => 'Shop Now',
                        'button_url' => '#',
                        'image_one_path' => null,
                        'image_two_path' => null,
                    ],
                    [
                        'title' => 'camera',
                        'title_highlight' => 'Sale',
                        'subtitle' => 'now start at $79',
                        'heading' => '70% off today',
                        'button_text' => 'Shop Now',
                        'button_url' => '#',
                        'image_one_path' => null,
                        'image_two_path' => null,
                    ],
                ],
                'collection_banners' => [
                    [
                        'title' => 'women',
                        'subtitle' => 'fashion',
                        'button_text' => 'shop now',
                        'button_url' => '#',
                        'image_path' => null,
                    ],
                    [
                        'title' => 'camera',
                        'subtitle' => 'lenses',
                        'button_text' => 'shop now',
                        'button_url' => '#',
                        'image_path' => null,
                    ],
                    [
                        'title' => 'refrigerator',
                        'subtitle' => 'lG mini',
                        'button_text' => 'shop now',
                        'button_url' => '#',
                        'image_path' => null,
                    ],
                ],
            ]
        );
    }
}
