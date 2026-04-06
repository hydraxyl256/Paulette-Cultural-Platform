<?php

namespace App\Filament\Resources\RbacRoleResource\Pages;

use App\Filament\Resources\RbacRoleResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListRbacRoles extends ListRecords
{
    protected static string $resource = RbacRoleResource::class;

    public function getView(): string
    {
        return 'filament.pages.rbac-roles';
    }

    // ── UI state ──────────────────────────────────────────────────
    public ?int   $ckSelectedRoleId    = null;
    public array  $ckChangedPerms      = []; // permission_name => bool
    public string $ckSearch            = '';

    // ── Select a role to edit permissions ─────────────────────────
    public function ckSelectRole(int $roleId): void
    {
        $this->ckSelectedRoleId = $roleId;
        $this->ckChangedPerms   = [];
    }

    // ── Toggle a permission on the ckChangedPerms staging ─────────
    public function ckTogglePerm(string $permName): void
    {
        $role = Role::find($this->ckSelectedRoleId);
        if (!$role) return;

        // Current actual state
        $current = $role->hasPermissionTo($permName);
        // If already overridden in staging, flip it; else flip from actual
        if (array_key_exists($permName, $this->ckChangedPerms)) {
            $this->ckChangedPerms[$permName] = !$this->ckChangedPerms[$permName];
        } else {
            $this->ckChangedPerms[$permName] = !$current;
        }
    }

    // ── Save all staged permission changes ─────────────────────────
    public function ckSaveChanges(): void
    {
        $role = Role::with('permissions')->find($this->ckSelectedRoleId);
        if (!$role) return;

        foreach ($this->ckChangedPerms as $permName => $grant) {
            $permission = Permission::where('name', $permName)->first();
            if (!$permission) {
                $permission = Permission::create(['name' => $permName, 'guard_name' => 'web']);
            }
            if ($grant) {
                $role->givePermissionTo($permission);
            } else {
                $role->revokePermissionTo($permission);
            }
        }
        $this->ckChangedPerms = [];
        Notification::make()->success()->title('Permissions Saved')
            ->body("Role '{$role->name}' permissions updated successfully.")->send();
    }

    // ── Discard staged changes ────────────────────────────────────
    public function ckDiscard(): void
    {
        $this->ckChangedPerms = [];
        Notification::make()->info()->title('Changes Discarded')->send();
    }

    // ── Create new role inline ─────────────────────────────────────
    public function ckCreateRole(string $name): void
    {
        if (blank($name)) return;
        Role::firstOrCreate(['name' => trim($name), 'guard_name' => 'web']);
        Notification::make()->success()->title('Role Created')
            ->body("Role '{$name}' created.")->send();
    }

    // ── Delete a role ─────────────────────────────────────────────
    public function ckDeleteRole(int $id): void
    {
        $role = Role::find($id);
        if ($role) {
            $name = $role->name;
            $role->delete();
            if ($this->ckSelectedRoleId === $id) {
                $this->ckSelectedRoleId = null;
                $this->ckChangedPerms = [];
            }
            Notification::make()->warning()->title('Role Deleted')
                ->body("'{$name}' has been deleted.")->send();
        }
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        // All roles with user counts
        $roles = Role::withCount('users as user_count')
            ->with('permissions')
            ->when($this->ckSearch, fn($q) => $q->where('name', 'like', "%{$this->ckSearch}%"))
            ->orderBy('id')
            ->get();

        // Selected role
        $selectedRole = $this->ckSelectedRoleId
            ? Role::with('permissions')->find($this->ckSelectedRoleId)
            : $roles->first();

        if ($selectedRole && !$this->ckSelectedRoleId) {
            $this->ckSelectedRoleId = $selectedRole->id;
        }

        // All permissions grouped by category (guard: web)
        $allPermissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($p) {
                // Group by prefix: "assets:upload" → "assets", "comics:read" → "comics"
                $parts = explode(':', $p->name, 2);
                return ucfirst($parts[0]);
            });

        // Predefined permission groups for the design-matching layout
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

        // Build permission states (actual + staged overrides)
        $permissionStates = [];
        if ($selectedRole) {
            $grantedNames = $selectedRole->permissions->pluck('name')->toArray();
            // Collect all permission names we care about
            $allPermNames = collect($permissionGroups)->flatten()->toArray();
            foreach ($allPermNames as $pName) {
                $actual = in_array($pName, $grantedNames);
                $permissionStates[$pName] = array_key_exists($pName, $this->ckChangedPerms)
                    ? $this->ckChangedPerms[$pName]
                    : $actual;
            }
        }

        // KPI stats
        $totalUsers  = User::count();
        $totalRoles  = $roles->count();
        $secureCore  = 98.2;
        $activeUsers = User::where('status', 'active')->count() ?: $totalUsers;

        // Role type meta (maps role name → type badge)
        $roleMeta = [
            'Super Admin'    => ['type' => 'SYSTEM LEVEL',  'color' => '#7c3aed', 'security' => 4],
            'Content Editor' => ['type' => 'ACTIVE VIEW',   'color' => '#059669', 'security' => 3],
            'Org Admin'      => ['type' => 'SCOPED',        'color' => '#d97706', 'security' => 3],
            'Moderator'      => ['type' => 'SUPPORT',       'color' => '#6b7280', 'security' => 1],
        ];

        // Sanctum token abilities (for the right panel, simulated per role)
        $tokenAbilities = [
            'assets:upload'     => true,
            'comics:read-only'  => true,
            'users:write'       => false,
            'meta:theme-engine' => true,
        ];

        return [
            'roles'            => $roles,
            'selectedRole'     => $selectedRole,
            'permissionGroups' => $permissionGroups,
            'permissionStates' => $permissionStates,
            'roleMeta'         => $roleMeta,
            'tokenAbilities'   => $tokenAbilities,
            'totalUsers'       => $totalUsers,
            'totalRoles'       => $totalRoles,
            'secureCore'       => $secureCore,
            'activeUsers'      => $activeUsers,
            'hasChanges'       => !empty($this->ckChangedPerms),
            'createUrl'        => RbacRoleResource::getUrl('create'),
            'auditUrl'         => rescue(fn () => route('filament.admin.resources.audit-logs-management.index'), RbacRoleResource::getUrl('index'), false),
        ];
    }
}
