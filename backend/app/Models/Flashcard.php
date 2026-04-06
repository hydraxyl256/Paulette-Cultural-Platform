<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flashcard extends Model
{
    protected $fillable = [
        'deck_id', 'front_text', 'back_text',
        'image_path', 'audio_path', 'order_index',
    ];

    public function deck(): BelongsTo
    {
        return $this->belongsTo(FlashcardDeck::class, 'deck_id');
    }
}
