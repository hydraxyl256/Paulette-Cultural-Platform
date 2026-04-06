<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChildProfile extends Model
{
    protected $fillable = [
        'parent_user_id',
        'org_id',
        'age_profile_id',
        'name',
        'date_of_birth',
        'avatar',
        'preferred_tribe_ids'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'preferred_tribe_ids' => 'array'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    public function ageProfile(): BelongsTo
    {
        return $this->belongsTo(AgeProfile::class, 'age_profile_id');
    }

    public function progressEvents(): HasMany
    {
        return $this->hasMany(ProgressEvent::class, 'child_id');
    }

    public function syncEvents(): HasMany
    {
        return $this->hasMany(SyncEvent::class, 'child_id');
    }

    public function getAge(): int
    {
        return (int) $this->date_of_birth->diffInYears(now());
    }
}
