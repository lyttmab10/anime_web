<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anime;

class CheckAnimeDataCommand extends Command
{
    protected $signature = 'anime:check-data';
    
    protected $description = 'Check the anime data in the database';

    public function handle()
    {
        $count = Anime::count();
        $this->info("Total anime records in database: {$count}");
        
        if ($count > 0) {
            $this->info("Sample records:");
            $animes = Anime::limit(5)->get();
            foreach ($animes as $anime) {
                $this->line("- {$anime->title} (ID: {$anime->id}, Rating: {$anime->rating})");
            }
        }
        
        $trendingCount = Anime::where('rating', '>=', 9.0)->count();
        $this->info("Trending anime count: {$trendingCount}");
    }
}