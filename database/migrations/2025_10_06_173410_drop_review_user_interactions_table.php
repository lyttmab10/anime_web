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
        Schema::dropIfExists('review_user_interactions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('review_user_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->enum('interaction_type', ['like', 'dislike']);
            $table->timestamps();
            
            $table->unique(['user_id', 'review_id']);
        });
    }
};
