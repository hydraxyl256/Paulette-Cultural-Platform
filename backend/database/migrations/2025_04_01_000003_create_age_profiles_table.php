<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_profiles', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('age_min')->unsigned();
            $table->tinyInteger('age_max')->unsigned();
            $table->string('stage', 50);
            $table->enum('ui_mode', ['simple', 'guided', 'advanced', 'full']);
            $table->tinyInteger('difficulty_ceiling')->default(3);
            $table->json('rules')->nullable();
            $table->timestamps();
            
            $table->unique(['age_min', 'age_max']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_profiles');
    }
};
