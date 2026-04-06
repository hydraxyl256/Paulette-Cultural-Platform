<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('label', 100);
            $table->string('subtitle', 255)->nullable();
            $table->string('emoji', 10)->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('key');
            $table->index('is_enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_flags');
    }
};
