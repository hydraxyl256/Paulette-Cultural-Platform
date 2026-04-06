<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('organisations')->cascadeOnDelete();
            $table->json('colors')->nullable();
            $table->json('typography')->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->json('custom_properties')->nullable();
            $table->timestamps();
            
            $table->unique('org_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_configs');
    }
};
