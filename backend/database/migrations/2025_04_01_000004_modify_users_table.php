<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add org_id and role if they don't exist
            if (!Schema::hasColumn('users', 'org_id')) {
                $table->foreignId('org_id')->default(1)->constrained('organisations')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', [
                    'super_admin',
                    'org_admin',
                    'cms_editor',
                    'teacher',
                    'parent',
                    'child'
                ])->default('parent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'org_id')) {
                $table->dropForeign(['org_id']);
                $table->dropColumn('org_id');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
