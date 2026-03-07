<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => self::prepareValue($value, $setting->type)]);
        } else {
            self::create([
                'key' => $key,
                'value' => self::prepareValue($value, 'string'),
                'type' => 'string',
                'group' => 'general',
                'label' => $key,
            ]);
        }

        Cache::forget("setting.{$key}");
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            return self::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => self::castValue($setting->value, $setting->type)];
                })
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }

    /**
     * Cast value to appropriate type
     */
    private static function castValue($value, string $type)
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Prepare value for storage
     */
    private static function prepareValue($value, string $type): string
    {
        return match ($type) {
            'json' => json_encode($value),
            'boolean' => $value ? 'true' : 'false',
            default => (string) $value,
        };
    }
}
