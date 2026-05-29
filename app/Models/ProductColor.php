<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'hex_code', 'owner_type', 'vendor_id', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function vendor() { return $this->belongsTo(Vendor::class); }
}
