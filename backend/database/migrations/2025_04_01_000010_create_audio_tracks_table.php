<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audio_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->foreignId('tribe_id')->nullable()->constrained('tribes')->nullOnDelete();
            $table->string('title');
            $table->string('subtitle')->nullable();          // e.g. "Drumming Session #04"
            $table->string('category')->default('general'); // yoruba_tribe, zulu_oral_history, nature_ambience, lullabies, general
            $table->enum('status', ['live', 'processing', 'archived'])->default('processing');
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->string('cover_image_path')->nullable();
            $table->string('audio_file_path')->nullable();
            $table->unsignedBigInteger('file_size_bytes')->default(0);
            $table->unsignedBigInteger('play_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audio_tracks');
    }
};
