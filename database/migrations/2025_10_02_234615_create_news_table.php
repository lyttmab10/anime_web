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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Author of the article
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('image_url')->nullable();
            $table->enum('type', ['news', 'blog', 'season_summary', 'analysis'])->default('news');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('author_name')->nullable(); // In case we want to show a custom author name
            $table->integer('views')->default(0);
            $table->json('tags')->nullable(); // For categorizing articles
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['type', 'is_published', 'published_at']);
            $table->index(['user_id']);
            $table->index(['slug']);
            $table->fullText(['title', 'content']); // For searching
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
