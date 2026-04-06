<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tribes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 100)->unique();
            $table->string('language', 100);
            $table->string('region', 150);
            $table->string('greeting', 100);
            $table->string('phonetic', 150);
            $table->string('color_hex', 7);
            $table->string('emoji_symbol', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('language');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tribes');
    }
};
