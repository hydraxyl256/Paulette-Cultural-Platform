<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressEvent extends Model
{
    protected $fillable = [
        'child_id',
        'comic_id',
        'tribe_id',
        'event_type',
        'panel_number',
        'duration_seconds',
        'score',
        'idempotency_key',
        'payload',
        'metadata',
        'recorded_at',
        'synced_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'metadata' => 'array',
        'recorded_at' => 'datetime',
        'synced_at' => 'datetime'
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(ChildProfile::class, 'child_id');
    }

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class, 'comic_id');
    }

    public function tribe(): BelongsTo
    {
        return $this->belongsTo(Tribe::class, 'tribe_id');
    }

    /**
     * Scope: Get only story completions
     */
    public function scopeStoryCompleted($query)
    {
        return $query->where('event_type', 'story_completed');
    }

    /**
     * Scope: Get only badge earned events
     */
    public function scopeBadgeEarned($query)
    {
        return $query->where('event_type', 'badge_earned');
    }

    /**
     * Scope: Get events from past N days
     */
    public function scopeLastDays($query, int $days)
    {
        return $query->where('recorded_at', '>=', now()->subDays($days));
    }
}
