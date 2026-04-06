<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_events', function (Blueprint $table) {
            // Add missing fields for offline sync
            if (!Schema::hasColumn('progress_events', 'tribe_id')) {
                $table->foreignId('tribe_id')->nullable()->constrained('tribes')->nullOnDelete();
            }
            if (!Schema::hasColumn('progress_events', 'panel_number')) {
                $table->unsignedTinyInteger('panel_number')->nullable();
            }
            if (!Schema::hasColumn('progress_events', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable();
            }
            if (!Schema::hasColumn('progress_events', 'score')) {
                $table->unsignedTinyInteger('score')->nullable();
            }
            if (!Schema::hasColumn('progress_events', 'metadata')) {
                $table->json('metadata')->nullable();
            }
            if (!Schema::hasColumn('progress_events', 'recorded_at')) {
                $table->timestamp('recorded_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('progress_events', function (Blueprint $table) {
            $table->dropForeignIdFor('Tribe');
            $table->dropColumn(['tribe_id', 'panel_number', 'duration_seconds', 'score', 'metadata', 'recorded_at']);
        });
    }
};
