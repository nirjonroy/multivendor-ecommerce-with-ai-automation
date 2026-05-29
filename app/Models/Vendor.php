<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'shop_name',
        'shop_slug',
        'email',
        'phone',
        'shop_email',
        'shop_phone',
        'shop_address',
        'shop_description',
        'kyc_business_type',
        'kyc_business_registration_number',
        'kyc_tax_id',
        'kyc_nid_number',
        'kyc_document_path',
        'kyc_status',
        'kyc_rejection_reason',
        'kyc_submitted_at',
        'kyc_reviewed_at',
        'password',
        'status',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'kyc_submitted_at' => 'datetime',
        'kyc_reviewed_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
