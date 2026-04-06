<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'plan',
        'modules',
        'theme_config',
        'is_active'
    ];

    protected $casts = [
        'modules' => 'array',
        'theme_config' => 'array',
        'is_active' => 'boolean'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'org_id');
    }

    public function childProfiles(): HasMany
    {
        return $this->hasMany(ChildProfile::class, 'org_id');
    }

    public function comics(): HasMany
    {
        return $this->hasMany(Comic::class, 'org_id');
    }

    public function themeConfig()
    {
        return $this->hasOne(ThemeConfig::class, 'org_id');
    }

    public function lessonPlans(): HasMany
    {
        return $this->hasMany(LessonPlan::class, 'org_id');
    }
}
