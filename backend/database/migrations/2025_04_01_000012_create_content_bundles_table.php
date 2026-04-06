<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('tribe_id')->nullable()->constrained('tribes')->nullOnDelete();
            $table->string('age_range')->default('3 - 6 Years');
            $table->string('deployment_version')->default('v1.0.0');
            $table->boolean('encryption_enabled')->default(true);
            $table->string('encryption_level')->default('Military Grade (AES-256)');
            $table->enum('status', ['draft', 'building', 'shipped', 'failed'])->default('draft');
            $table->unsignedBigInteger('bundle_size_bytes')->default(0);
            $table->unsignedTinyInteger('build_readiness_pct')->default(0); // 0-100
            $table->unsignedInteger('bandwidth_mbps')->default(0);
            $table->json('selected_asset_ids')->nullable(); // IDs from comics, audio, flashcards
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_bundles');
    }
};
