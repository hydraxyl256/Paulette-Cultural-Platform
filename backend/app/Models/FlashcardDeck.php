<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class FlashcardDeck extends Model
{
    protected $fillable = [
        'org_id', 'tribe_id', 'name', 'subtitle',
        'cover_image_path', 'age_min', 'age_max',
        'status', 'engagement_rate', 'is_global',
    ];

    protected $casts = [
        'is_global'       => 'boolean',
        'age_min'         => 'integer',
        'age_max'         => 'integer',
        'engagement_rate' => 'integer',
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    public function tribe(): BelongsTo
    {
        return $this->belongsTo(Tribe::class, 'tribe_id');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Flashcard::class, 'deck_id')->orderBy('order_index');
    }

    /** Engagement rate as human-readable percentage e.g. "84.2%" */
    public function engagementRateFormatted(): string
    {
        return number_format($this->engagement_rate / 100, 1) . '%';
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', 'live');
    }
}
