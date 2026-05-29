<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'sku', 'category_id', 'sub_category_id', 'child_category_id',
        'brand_id', 'owner_type', 'vendor_id', 'thumbnail_path', 'gallery_paths',
        'video_url', 'price', 'offer_price', 'stock_quantity', 'short_description',
        'long_description', 'sizes', 'colors', 'size_prices', 'color_images',
        'variation_stocks', 'has_variation_stock', 'status', 'is_featured',
        'is_new', 'is_on_sale', 'sort_order',
    ];

    protected $casts = [
        'gallery_paths' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'size_prices' => 'array',
        'color_images' => 'array',
        'variation_stocks' => 'array',
        'has_variation_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function subCategory() { return $this->belongsTo(SubCategory::class); }
    public function childCategory() { return $this->belongsTo(ChildCategory::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
}
