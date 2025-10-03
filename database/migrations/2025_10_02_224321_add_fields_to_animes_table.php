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
        Schema::table('animes', function (Blueprint $table) {
            $table->string('studio')->nullable();
            $table->string('season')->nullable();
            $table->integer('episodes')->nullable();
            $table->json('genres')->nullable(); // Store as JSON array
            $table->json('characters')->nullable(); // Store as JSON array
            $table->string('trailer_url')->nullable();
            $table->string('official_site')->nullable();
            $table->string('status')->default('not_yet_aired'); // Options: not_yet_aired, currently_airing, finished_airing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn([
                'studio',
                'season',
                'episodes',
                'genres',
                'characters',
                'trailer_url',
                'official_site',
                'status'
            ]);
        });
    }
};
