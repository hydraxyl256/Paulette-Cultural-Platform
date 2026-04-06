<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AudioTrack extends Model
{
    protected $fillable = [
        'org_id',
        'tribe_id',
        'title',
        'subtitle',
        'category',
        'status',
        'duration_seconds',
        'cover_image_path',
        'audio_file_path',
        'file_size_bytes',
        'play_count',
        'download_count',
        'is_featured',
    ];

    protected $casts = [
        'is_featured'      => 'boolean',
        'play_count'       => 'integer',
        'download_count'   => 'integer',
        'duration_seconds' => 'integer',
        'file_size_bytes'  => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    public function tribe(): BelongsTo
    {
        return $this->belongsTo(Tribe::class, 'tribe_id');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function formattedDuration(): string
    {
        $s = $this->duration_seconds ?? 0;
        return sprintf('%02d:%02d', intdiv($s, 60), $s % 60);
    }

    public function formattedFileSize(): string
    {
        $bytes = $this->file_size_bytes ?? 0;
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 1) . ' GB';
        if ($bytes >= 1048576)   return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)      return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', 'live');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
