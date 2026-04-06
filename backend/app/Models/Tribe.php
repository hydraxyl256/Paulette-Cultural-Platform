<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Tribe extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'language',
        'region',
        'greeting',
        'phonetic',
        'color_hex',
        'emoji_symbol',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function comics(): HasMany
    {
        return $this->hasMany(Comic::class, 'tribe_id');
    }

    /**
     * Scope to get only active tribes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
