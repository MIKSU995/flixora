<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->string('user_identifier'); // Unique cookie / session identifier for guest
            $table->tinyInteger('rating'); // 1-5 stars
            $table->timestamps();

            $table->unique(['media_id', 'user_identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
