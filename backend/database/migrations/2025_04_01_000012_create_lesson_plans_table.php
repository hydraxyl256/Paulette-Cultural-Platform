<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('classroom_id', 100);
            $table->string('title', 255);
            $table->json('assigned_comic_ids')->nullable();
            $table->json('assigned_tribe_ids')->nullable();
            $table->dateTime('scheduled_at');
            $table->enum('status', ['draft', 'scheduled', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
            
            $table->index('org_id');
            $table->index('teacher_id');
            $table->index('classroom_id');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_plans');
    }
};
