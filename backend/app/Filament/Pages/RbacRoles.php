<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Filament\Resources\RbacRoleResource;
use Filament\Notifications\Notification;

class RbacRoles extends Page
{
    // ── Page settings ─────────────────────────────────────────────
    protected string $view = 'filament.pages.rbac-roles';

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-shield-check';
    protected static UnitEnum|string|null   $navigationGroup = 'SYSTEM';
    protected static ?int    $navigationSort  = 3;
    protected static ?string $title           = 'Access Control';
    protected static ?string $navigationLabel = 'RBAC Roles';

    // ── Livewire state ────────────────────────────────────────────
    public ?int   $ckSelectedRoleId = null;
    public array  $ckChangedPerms   = [];
    public string $ckSearch         = '';

    public function mount(): void
    {
        $first = Role::first();
        if ($first) {
            $this->ckSelectedRoleId = $first->id;
        }
    }

    // ── Select a role to edit ─────────────────────────────────────
    public function ckSelectRole(int $roleId): void
    {
        $this->ckSelectedRoleId = $roleId;
        $this->ckChangedPerms   = [];
    }

    // ── Toggle permission in staging ──────────────────────────────
    public function ckTogglePerm(string $permName): void
    {
        $role = Role::find($this->ckSelectedRoleId);
        if (!$role) return;

        $current = $role->hasPermissionTo($permName);
        if (array_key_exists($permName, $this->ckChangedPerms)) {
            $this->ckChangedPerms[$permName] = !$this->ckChangedPerms[$permName];
        } else {
            $this->ckChangedPerms[$permName] = !$current;
        }
    }

    // ── Save staged permission changes ────────────────────────────
    public function ckSaveChanges(): void
    {
        $role = Role::with('permissions')->find($this->ckSelectedRoleId);
        if (!$role) return;

        foreach ($this->ckChangedPerms as $permName => $grant) {
            $permission = Permission::firstOrCreate(
                ['name' => $permName, 'guard_name' => 'web']
            );
            $grant
                ? $role->givePermissionTo($permission)
                : $role->revokePermissionTo($permission);
        }
        $this->ckChangedPerms = [];
        Notification::make()->success()
            ->title('Permissions Saved')
            ->body("Role '{$role->name}' updated successfully.")
            ->send();
    }

    // ── Discard staged changes ────────────────────────────────────
    public function ckDiscard(): void
    {
        $this->ckChangedPerms = [];
        Notification::make()->info()->title('Changes Discarded')->send();
    }

    // ── Delete a role ─────────────────────────────────────────────
    public function ckDeleteRole(int $id): void
    {
        $role = Role::find($id);
        if (!$role) return;
        $name = $role->name;
        $role->delete();
        if ($this->ckSelectedRoleId === $id) {
            $this->ckSelectedRoleId = null;
            $this->ckChangedPerms   = [];
        }
        Notification::make()->warning()->title('Role Deleted')
            ->body("'{$name}' has been deleted.")->send();
    }

    // ── View data passed to blade ─────────────────────────────────
    protected function getViewData(): array
    {
        $roles = Role::withCount('users as user_count')
            ->with('permissions')
            ->when($this->ckSearch, fn($q) => $q->where('name', 'like', "%{$this->ckSearch}%"))
            ->orderBy('id')
            ->get();

        $selectedRole = $this->ckSelectedRoleId
            ? Role::with('permissions')->find($this->ckSelectedRoleId)
            : $roles->first();

        if ($selectedRole && !$this->ckSelectedRoleId) {
            $this->ckSelectedRoleId = $selectedRole->id;
        }

        $permissionGroups = [
            'Catalog Management' => [
                'create-exhibit', 'modify-metadata', 'hard-delete-artifacts',
                'publish-content', 'manage-tribes',
            ],
            'System Operations' => [
                'view-analytics', 'export-raw-data', 'manage-users',
                'manage-roles', 'system-settings',
            ],
            'Content Access' => [
                'assets:upload', 'comics:read-only', 'users:write',
                'meta:theme-engine', 'flashcards:manage',
            ],
        ];

        $permissionStates = [];
        if ($selectedRole) {
            $grantedNames = $selectedRole->permissions->pluck('name')->toArray();
            $allPermNames = collect($permissionGroups)->flatten()->toArray();
            foreach ($allPermNames as $pName) {
                $actual = in_array($pName, $grantedNames);
                $permissionStates[$pName] = array_key_exists($pName, $this->ckChangedPerms)
                    ? $this->ckChangedPerms[$pName]
                    : $actual;
            }
        }

        $totalUsers  = User::count();
        $totalRoles  = $roles->count();
        $activeUsers = User::where('status', 'active')->count() ?: $totalUsers;

        $auditUrl = rescue(
            fn() => route('filament.admin.resources.audit-logs-management.index'),
            static::getUrl(),
            false
        );

        return [
            'roles'            => $roles,
            'selectedRole'     => $selectedRole,
            'permissionGroups' => $permissionGroups,
            'permissionStates' => $permissionStates,
            'totalUsers'       => $totalUsers,
            'totalRoles'       => $totalRoles,
            'secureCore'       => 98.2,
            'activeUsers'      => $activeUsers,
            'hasChanges'       => !empty($this->ckChangedPerms),
            'createUrl'        => rescue(fn() => RbacRoleResource::getUrl('create'), '/admin/rbac-role-forms/create', false),
            'auditUrl'         => $auditUrl,
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
