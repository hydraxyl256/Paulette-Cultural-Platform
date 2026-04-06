<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('tribe_id')->constrained('tribes')->cascadeOnDelete();
            $table->string('title', 255);
            $table->tinyInteger('age_min')->unsigned()->default(2);
            $table->tinyInteger('age_max')->unsigned()->default(6);
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->string('cover_image_path', 500)->nullable();
            $table->string('bundle_path', 500)->nullable();
            $table->string('bundle_hash', 64)->nullable();
            $table->timestamps();
            
            $table->index('org_id');
            $table->index('tribe_id');
            $table->index('status');
            $table->index(['org_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
