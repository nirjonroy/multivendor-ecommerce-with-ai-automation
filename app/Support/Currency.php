<?php

namespace App\Support;

use App\Models\SiteInfo;

class Currency
{
    public static function format(float|int|string|null $amount, ?SiteInfo $siteInfo = null): string
    {
        $siteInfo ??= app()->bound('globalSiteInfo') ? app('globalSiteInfo') : null;
        $amount = (float) ($amount ?? 0);
        $rate = (float) ($siteInfo?->currency_rate ?: 1);
        $symbol = $siteInfo?->currency_symbol ?: '$';
        $position = $siteInfo?->currency_position ?: 'left';
        $converted = number_format($amount * $rate, 2);

        return $position === 'right' ? $converted . ' ' . $symbol : $symbol . $converted;
    }
}
