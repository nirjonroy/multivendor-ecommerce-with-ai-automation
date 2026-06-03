<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever(self::cacheKey($key), function () use ($key, $default) {
            return self::query()->where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget(self::cacheKey($key));
    }

    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
    }

    private static function cacheKey(string $key): string
    {
        return 'setting:' . $key;
    }
}
