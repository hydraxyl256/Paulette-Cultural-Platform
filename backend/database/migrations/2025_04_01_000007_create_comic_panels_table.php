<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comic_panels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comic_id')->constrained('comics')->cascadeOnDelete();
            $table->unsignedTinyInteger('order_index');
            $table->string('image_path', 500);
            $table->json('vocab_tags')->nullable();
            $table->string('audio_path', 500)->nullable();
            $table->timestamps();
            
            $table->index('comic_id');
            $table->unique(['comic_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comic_panels');
    }
};
