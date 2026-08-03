<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['movie', 'tv_show'])->default('movie');
            $table->text('description');
            $table->integer('release_year');
            $table->string('duration_or_seasons'); // e.g. "2j 15m" or "3 Season"
            $table->string('poster_url');
            $table->string('banner_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->string('director')->nullable();
            $table->text('cast')->nullable();
            $table->decimal('avg_rating', 3, 1)->default(0.0);
            $table->integer('total_ratings')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
