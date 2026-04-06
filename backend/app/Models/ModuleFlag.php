<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleFlag extends Model
{
    protected $fillable = [
        'key',
        'label',
        'subtitle',
        'emoji',
        'is_enabled',
        'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Check if a module is enabled by key.
     */
    public static function isEnabled(string $key): bool
    {
        $flag = static::where('key', $key)->first();

        return $flag ? $flag->is_enabled : false;
    }

    /**
     * Toggle a module's enabled state.
     */
    public static function toggle(string $key): bool
    {
        $flag = static::where('key', $key)->first();

        if (! $flag) {
            return false;
        }

        $flag->is_enabled = ! $flag->is_enabled;
        $flag->save();

        return $flag->is_enabled;
    }

    /**
     * Get all module definitions ordered by sort_order.
     */
    public static function allOrdered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::orderBy('sort_order')->get();
    }

    /**
     * Seed default modules if table is empty.
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            ['key' => 'comics', 'label' => 'Comics', 'subtitle' => 'Story panel viewer', 'emoji' => '📖', 'is_enabled' => true, 'sort_order' => 1],
            ['key' => 'songs', 'label' => 'Songs & Audio', 'subtitle' => 'Music + pronunciation', 'emoji' => '🎵', 'is_enabled' => true, 'sort_order' => 2],
            ['key' => 'flashcards', 'label' => 'Flashcards', 'subtitle' => 'Vocab + language', 'emoji' => '🃏', 'is_enabled' => true, 'sort_order' => 3],
            ['key' => 'offline_bundles', 'label' => 'Offline Bundles', 'subtitle' => '.ckb download system', 'emoji' => '📦', 'is_enabled' => true, 'sort_order' => 4],
            ['key' => 'theme_engine', 'label' => 'Theme Engine', 'subtitle' => 'Org branding override', 'emoji' => '🎨', 'is_enabled' => true, 'sort_order' => 5],
            ['key' => 'kiosk', 'label' => 'Kiosk Mode', 'subtitle' => 'Classroom tablets', 'emoji' => '🖥️', 'is_enabled' => false, 'sort_order' => 6],
        ];

        foreach ($defaults as $module) {
            static::firstOrCreate(['key' => $module['key']], $module);
        }
    }
}
