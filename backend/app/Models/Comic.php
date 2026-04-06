<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Comic extends Model
{
    protected $fillable = [
        'org_id',
        'tribe_id',
        'title',
        'age_min',
        'age_max',
        'status',
        'cover_image_path',
        'bundle_path',
        'bundle_hash'
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    public function tribe(): BelongsTo
    {
        return $this->belongsTo(Tribe::class, 'tribe_id');
    }

    public function panels(): HasMany
    {
        return $this->hasMany(ComicPanel::class, 'comic_id')->orderBy('order_index');
    }

    public function progressEvents(): HasMany
    {
        return $this->hasMany(ProgressEvent::class, 'comic_id');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Scope to get only published comics
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
