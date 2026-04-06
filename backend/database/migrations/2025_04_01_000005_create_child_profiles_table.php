<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('org_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('age_profile_id')->constrained('age_profiles');
            $table->string('name', 100);
            $table->date('date_of_birth');
            $table->string('avatar', 255)->nullable();
            $table->json('preferred_tribe_ids')->nullable();
            $table->timestamps();
            
            $table->index('parent_user_id');
            $table->index('org_id');
            $table->index('age_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_profiles');
    }
};
