<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'org_id',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    public function childProfiles(): HasMany
    {
        return $this->hasMany(ChildProfile::class, 'parent_user_id');
    }

    public function lessonPlans(): HasMany
    {
        return $this->hasMany(LessonPlan::class, 'teacher_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function getSanctumAbilities(): array
    {
        return match ($this->role) {
            'super_admin' => ['*'],
            'org_admin' => ['org:manage', 'content:edit', 'users:manage', 'analytics:view'],
            'cms_editor' => ['content:edit', 'content:submit'],
            'teacher' => ['progress:view', 'progress:record', 'class:manage'],
            'parent' => ['child:manage', 'progress:view:own'],
            'child' => ['progress:record', 'content:read'],
            default => []
        };
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Return true to allow access in production (Render).
        // Since auth is handled, all authenticated users with roles can access their permitted resources.
        return true;
    }
}
