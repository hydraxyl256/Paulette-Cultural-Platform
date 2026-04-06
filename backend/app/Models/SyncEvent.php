<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncEvent extends Model
{
    protected $fillable = [
        'child_id',
        'event_type',
        'payload',
        'idempotency_key',
        'processed',
        'processed_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime'
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(ChildProfile::class, 'child_id');
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false);
    }
}
