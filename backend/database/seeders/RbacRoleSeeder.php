<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RbacRoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Catalog Management
            'create-exhibit',
            'modify-metadata',
            'hard-delete-artifacts',
            'publish-content',
            'manage-tribes',
            // System Operations
            'view-analytics',
            'export-raw-data',
            'manage-users',
            'manage-roles',
            'system-settings',
            // Content Access (Sanctum-style)
            'assets:upload',
            'comics:read-only',
            'users:write',
            'meta:theme-engine',
            'flashcards:manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Super Admin — all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        // Content Editor — catalog + content access
        $contentEditor = Role::firstOrCreate(['name' => 'Content Editor', 'guard_name' => 'web']);
        $contentEditor->syncPermissions([
            'create-exhibit', 'modify-metadata', 'publish-content',
            'assets:upload', 'comics:read-only', 'meta:theme-engine', 'flashcards:manage',
            'view-analytics',
        ]);

        // Org Admin — org-scoped
        $orgAdmin = Role::firstOrCreate(['name' => 'Org Admin', 'guard_name' => 'web']);
        $orgAdmin->syncPermissions([
            'create-exhibit', 'modify-metadata', 'manage-users',
            'view-analytics', 'assets:upload', 'manage-tribes',
        ]);

        // Moderator — minimal
        $moderator = Role::firstOrCreate(['name' => 'Moderator', 'guard_name' => 'web']);
        $moderator->syncPermissions([
            'view-analytics', 'comics:read-only',
        ]);

        $this->command->info('✅ Seeded ' . Role::count() . ' roles with ' . Permission::count() . ' permissions.');
    }
}
