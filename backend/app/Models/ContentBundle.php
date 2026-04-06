<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentBundle extends Model
{
    protected $fillable = [
        'title', 'tribe_id', 'age_range', 'deployment_version',
        'encryption_enabled', 'encryption_level', 'status',
        'bundle_size_bytes', 'build_readiness_pct',
        'bandwidth_mbps', 'selected_asset_ids',
    ];

    protected $casts = [
        'encryption_enabled'  => 'boolean',
        'selected_asset_ids'  => 'array',
        'build_readiness_pct' => 'integer',
        'bundle_size_bytes'   => 'integer',
        'bandwidth_mbps'      => 'integer',
    ];

    public function tribe(): BelongsTo
    {
        return $this->belongsTo(Tribe::class, 'tribe_id');
    }

    public function formattedSize(): string
    {
        $bytes = $this->bundle_size_bytes ?? 0;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)   return number_format($bytes / 1048576, 1) . ' MB';
        return number_format($bytes / 1024, 0) . ' KB';
    }
}
