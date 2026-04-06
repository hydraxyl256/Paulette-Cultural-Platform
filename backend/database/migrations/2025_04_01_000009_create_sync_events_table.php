<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('child_profiles')->cascadeOnDelete();
            $table->string('event_type', 100);
            $table->json('payload');
            $table->string('idempotency_key', 64)->unique();
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index('child_id');
            $table->index('processed');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_events');
    }
};
