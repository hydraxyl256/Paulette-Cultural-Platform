<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonPlan extends Model
{
    protected $fillable = [
        'org_id',
        'teacher_id',
        'classroom_id',
        'title',
        'assigned_comic_ids',
        'assigned_tribe_ids',
        'scheduled_at',
        'status'
    ];

    protected $casts = [
        'assigned_comic_ids' => 'array',
        'assigned_tribe_ids' => 'array',
        'scheduled_at' => 'datetime'
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
