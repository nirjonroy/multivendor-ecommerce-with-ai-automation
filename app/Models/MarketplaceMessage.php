<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'vendor_id',
        'admin_id',
        'sender_type',
        'recipient_type',
        'subject',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function admin() { return $this->belongsTo(Admin::class); }
}
