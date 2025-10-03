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
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('anime_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['watching', 'completed', 'planned', 'on_hold', 'dropped'])->default('planned');
            $table->integer('progress')->default(0); // For tracking episode progress
            $table->text('notes')->nullable(); // Personal notes about the anime
            $table->timestamps();
            
            // Add indexes
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'anime_id']); // For checking if anime is already in a user's watchlist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
