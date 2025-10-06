<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anime;
use App\Services\AnimeInfoService;

class UpdateAnimeInfoCommand extends Command
{
    protected $signature = 'anime:update-info {--all : Update all anime, otherwise only those without existing info}';
    
    protected $description = 'Fetch and update detailed information for anime';

    public function handle(AnimeInfoService $animeInfoService)
    {
        $this->info('Starting to update anime information...');
        
        $query = Anime::query();
        
        if (!$this->option('all')) {
            $query->where(function($q) {
                $q->whereNull('studio')
                  ->orWhereNull('episodes')
                  ->orWhereNull('status')
                  ->orWhereNull('season');
            });
        }
        
        $animes = $query->get();
        
        $total = $animes->count();
        $updated = 0;
        
        if ($total === 0) {
            $this->info('No anime found to update.');
            return 0;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($animes as $anime) {
            $this->info("\nProcessing: " . $anime->title);
            
            $info = $animeInfoService->getAnimeInfo($anime->title);
            
            if ($info) {
                $anime->update([
                    'studio' => $info['studio'] ?? $anime->studio,
                    'episodes' => $info['episodes'] ?? $anime->episodes,
                    'status' => $info['status'] ?? $anime->status,
                    'season' => $info['season'] ?? $anime->season,
                    'release_date' => $info['release_date'] ?? $anime->release_date,
                ]);
                
                $this->info("  Updated: " . $anime->title);
                $updated++;
            } else {
                $this->warn("  No data found for: " . $anime->title);
            }
            
            $bar->advance();
            // Sleep briefly to avoid hitting API rate limits
            sleep(1);
        }

        $bar->finish();
        $this->newLine();
        
        $this->info("\nCompleted! Updated {$updated} out of {$total} anime entries.");
        
        return 0;
    }
}