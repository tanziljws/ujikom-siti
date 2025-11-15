<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    
    protected $fillable = [
        'key',
        'label',
        'type',
        'value',
        'group',
        'description',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Boot method to clear cache when settings change
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('site_settings');
        });

        static::deleted(function () {
            Cache::forget('site_settings');
        });
    }

    /**
     * Get all settings grouped by group
     */
    public static function getAllGrouped()
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::orderBy('group')->orderBy('order')->get()->groupBy('group');
        });
    }

    /**
     * Get a single setting value by key
     */
    public static function get($key, $default = null)
    {
        try {
            if (!\Schema::hasTable('site_settings')) {
                return $default;
            }
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            \Log::warning('SiteSetting::get error: ' . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllAsKeyValue()
    {
        return Cache::remember('site_settings_kv', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }
}
