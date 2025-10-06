<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetGenresCommand extends Command
{
    protected $signature = 'anime:get-genres';
    
    protected $description = 'Get all unique genres from the anime database';

    public function handle()
    {
        // Get all unique genres from the database
        $genres = DB::table('animes')
            ->select('genres')
            ->whereNotNull('genres')
            ->get()
            ->pluck('genres')
            ->filter(function ($genres) {
                return !is_null($genres) && !empty($genres) && is_array($genres);
            })
            ->flatten()
            ->unique()
            ->values()
            ->sort()
            ->all();
        
        $this->info('Available genres:');
        foreach ($genres as $genre) {
            $this->line("- " . $genre);
        }
        
        return 0;
    }
}