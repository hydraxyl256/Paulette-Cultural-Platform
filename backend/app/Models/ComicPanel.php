<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComicPanel extends Model
{
    protected $fillable = [
        'comic_id',
        'order_index',
        'image_path',
        'vocab_tags',
        'audio_path'
    ];

    protected $casts = [
        'vocab_tags' => 'array'
    ];

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class, 'comic_id');
    }
}
