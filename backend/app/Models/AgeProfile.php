<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgeProfile extends Model
{
    protected $fillable = [
        'age_min',
        'age_max',
        'stage',
        'ui_mode',
        'difficulty_ceiling',
        'rules'
    ];

    protected $casts = [
        'rules' => 'array'
    ];

    public function childProfiles(): HasMany
    {
        return $this->hasMany(ChildProfile::class, 'age_profile_id');
    }

    public function getNameAttribute(): string
    {
        return "{$this->age_min}–{$this->age_max} ({$this->stage})";
    }
}
