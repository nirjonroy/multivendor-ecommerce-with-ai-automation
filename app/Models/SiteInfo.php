<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_url',
        'contact_email',
        'contact_phone',
        'address',
        'short_description',
        'currency_code',
        'currency_symbol',
        'currency_position',
        'currency_rate',
        'newsletter_popup_enabled',
        'newsletter_popup_title',
        'newsletter_popup_description',
        'newsletter_popup_button_text',
        'newsletter_popup_image_path',
        'logo_path',
        'favicon_path',
        'facebook_url',
        'instagram_url',
        'youtube_url',
    ];

    protected $casts = [
        'newsletter_popup_enabled' => 'boolean',
    ];
}
