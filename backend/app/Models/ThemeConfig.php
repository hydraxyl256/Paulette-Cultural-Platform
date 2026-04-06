<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemeConfig extends Model
{
    protected $fillable = [
        'org_id',
        'colors',
        'typography',
        'logo_url',
        'custom_properties'
    ];

    protected $casts = [
        'colors' => 'array',
        'typography' => 'array',
        'custom_properties' => 'array'
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }
}
