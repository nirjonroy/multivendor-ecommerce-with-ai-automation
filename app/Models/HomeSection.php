<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'top_brands',
        'hero_slides',
        'collection_banners',
        'content_blocks',
    ];

    protected $casts = [
        'hero_slides' => 'array',
        'collection_banners' => 'array',
        'content_blocks' => 'array',
    ];
}
