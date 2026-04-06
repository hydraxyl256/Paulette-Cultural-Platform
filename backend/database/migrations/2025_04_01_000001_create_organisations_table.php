<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 100)->unique();
            $table->enum('plan', ['free', 'school', 'enterprise']);
            $table->json('modules')->default('["comics", "songs", "vocab", "offline"]');
            $table->json('theme_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
