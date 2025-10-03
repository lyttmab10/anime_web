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
        Schema::create('user_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user who is following
            $table->foreignId('related_user_id')->constrained('users')->onDelete('cascade'); // The user being followed
            $table->enum('type', ['friend', 'follow'])->default('follow'); // Type of relationship
            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending'); // Status of relationship
            $table->timestamps();
            
            // Prevent duplicate relationships
            $table->unique(['user_id', 'related_user_id', 'type']);
            
            // Indexes for better performance
            $table->index(['user_id', 'type']);
            $table->index(['related_user_id', 'type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relationships');
    }
};
