<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('anime_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->nullable(); // Rating from 1 to 5 (stars)
            $table->text('review')->nullable(); // Review text
            $table->integer('likes')->default(0); // For upvotes
            $table->integer('dislikes')->default(0); // For downvotes
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['anime_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
