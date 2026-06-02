<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'transaction_id',
        'payment_method',
        'payment_status',
        'status',
        'subtotal',
        'shipping_amount',
        'total',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
