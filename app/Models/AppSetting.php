<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = ['key', 'value', 'type', 'label', 'group', 'sort_order'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function allByGroup(): array
    {
        return static::orderBy('group')->orderBy('sort_order')->get()
            ->groupBy('group')
            ->toArray();
    }

    public static function flushCache(): void
    {
        static::all()->each(fn ($s) => Cache::forget("setting_{$s->key}"));
    }
}
