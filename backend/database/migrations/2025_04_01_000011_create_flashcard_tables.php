<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Flashcard Decks ──────────────────────────────────────
        Schema::create('flashcard_decks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->foreignId('tribe_id')->nullable()->constrained('tribes')->nullOnDelete();
            $table->string('name');
            $table->string('subtitle')->nullable();          // e.g. "Vocabulary & Pronunciation"
            $table->string('cover_image_path')->nullable();
            $table->unsignedTinyInteger('age_min')->default(3);
            $table->unsignedTinyInteger('age_max')->default(12);
            $table->enum('status', ['live', 'draft', 'archived'])->default('draft');
            $table->unsignedBigInteger('engagement_rate')->default(0); // stored as pct * 100 (e.g. 8420 = 84.20%)
            $table->boolean('is_global')->default(false);
            $table->timestamps();
        });

        // ── Individual Flashcards ────────────────────────────────
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained('flashcard_decks')->cascadeOnDelete();
            $table->string('front_text');
            $table->string('back_text')->nullable();
            $table->string('image_path')->nullable();
            $table->string('audio_path')->nullable();
            $table->unsignedSmallInteger('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flashcards');
        Schema::dropIfExists('flashcard_decks');
    }
};
