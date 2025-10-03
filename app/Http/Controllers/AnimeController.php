<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;

class AnimeController extends Controller
{
    public function index()
    {
        // Get one random anime for "Anime of the Day"
        $featuredAnime = Anime::inRandomOrder()->first();
        
        // Get trending and new release anime for the 4x5 grid (20 items)
        // First, get trending anime ordered by rating
        $trendingAnimes = Anime::where('is_trending', true)
            ->orderByRaw("CASE 
                WHEN title = 'ดาบพิฆาตอสูร' THEN 1
                WHEN title = 'โจมตียักษ์' THEN 2
                ELSE 3
            END")
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get();
        
        // Then, get recently added anime (not already in trending list) if we need more to fill the grid
        $recentAnimes = collect();
        $neededCount = 20 - $trendingAnimes->count();
        
        if ($neededCount > 0) {
            $recentAnimes = Anime::where('is_trending', false)
                ->orderBy('release_date', 'desc')
                ->limit($neededCount)
                ->get();
        }
        
        // Combine trending and recent anime to make 20 items
        $trendingAndRecentAnimes = $trendingAnimes->concat($recentAnimes)->take(20);

        return view('welcome', compact('featuredAnime', 'trendingAndRecentAnimes'));
    }
    
    public function show(Anime $anime)
    {
        // Get similar anime based on genres
        $similarAnime = collect();
        if ($anime->genres && is_array($anime->genres)) {
            $similarAnime = Anime::where('id', '!=', $anime->id)
                ->where(function($query) use ($anime) {
                    foreach ($anime->genres as $genre) {
                        $query->orWhereJsonContains('genres', $genre);
                    }
                })
                ->limit(6)
                ->get();
        }
        
        return view('anime.show', compact('anime', 'similarAnime'));
    }
    
    public function random()
    {
        $randomAnime = Anime::inRandomOrder()->first();
        
        if ($randomAnime) {
            return redirect()->route('anime.show', $randomAnime);
        }
        
        // If no anime found, redirect back to home
        return redirect()->route('home')->with('error', 'ไม่พบอนิเมะในระบบ');
    }
    
    public function compareForm()
    {
        $animes = Anime::orderBy('title')->get();
        return view('anime.compare-form', compact('animes'));
    }
    
    public function compare(Request $request)
    {
        $request->validate([
            'anime1_id' => 'required|exists:animes,id',
            'anime2_id' => 'required|exists:animes,id|different:anime1_id',
            'anime3_id' => 'nullable|exists:animes,id|different:anime1_id,anime2_id',
            'anime4_id' => 'nullable|exists:animes,id|different:anime1_id,anime2_id,anime3_id',
        ]);
        
        $animeIds = collect([$request->anime1_id, $request->anime2_id, $request->anime3_id, $request->anime4_id])
            ->filter() // Remove null values
            ->take(4) // Take up to 4 anime
            ->toArray();
        
        $animes = Anime::whereIn('id', $animeIds)->get();
        
        return view('anime.compare', compact('animes'));
    }
}