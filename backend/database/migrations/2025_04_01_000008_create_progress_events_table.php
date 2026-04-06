<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('child_profiles')->cascadeOnDelete();
            $table->foreignId('comic_id')->nullable()->constrained('comics')->nullOnDelete();
            $table->enum('event_type', [
                'story_start',
                'story_complete',
                'badge_earned',
                'vocab_seen',
                'activity_complete'
            ]);
            $table->string('idempotency_key', 64)->unique();
            $table->json('payload')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->index('child_id');
            $table->index('event_type');
            $table->index(['child_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_events');
    }
};
