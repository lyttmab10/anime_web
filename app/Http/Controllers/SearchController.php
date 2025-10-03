<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $genre = $request->input('genre', '');
        $year = $request->input('year', '');
        $season = $request->input('season', '');
        $studio = $request->input('studio', '');
        
        $animes = Anime::query();
        
        // Search functionality - search in title, characters, and studio
        if (!empty($query)) {
            $animes = $animes->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('studio', 'LIKE', "%{$query}%")
                  ->orWhereJsonContains('characters', $query);
            });
        }
        
        // Filter by genre
        if (!empty($genre)) {
            $animes = $animes->whereJsonContains('genres', $genre);
        }
        
        // Filter by year
        if (!empty($year)) {
            $animes = $animes->whereYear('release_date', $year);
        }
        
        // Filter by season
        if (!empty($season)) {
            $animes = $animes->where('season', $season);
        }
        
        // Filter by studio
        if (!empty($studio)) {
            $animes = $animes->where('studio', 'LIKE', "%{$studio}%");
        }
        
        $animes = $animes->paginate(20);
        
        // Get distinct years for the filter dropdown
        $years = Anime::selectRaw('YEAR(release_date) as year')
                     ->whereNotNull('release_date')
                     ->distinct()
                     ->pluck('year')
                     ->sort()->values();
        
        // Get all unique genres for the filter dropdown
        $genres = Anime::selectRaw('JSON_EXTRACT(genres, "$[*]") as genre')
                      ->pluck('genre')
                      ->flatten()
                      ->unique()
                      ->filter()
                      ->values();
        
        // Get all unique seasons for the filter dropdown
        $seasons = Anime::whereNotNull('season')
                       ->distinct()
                       ->pluck('season');
        
        // Get all unique studios for the filter dropdown
        $studios = Anime::whereNotNull('studio')
                       ->distinct()
                       ->pluck('studio');
        
        return view('search.index', compact('animes', 'query', 'genre', 'year', 'season', 'studio', 'years', 'genres', 'seasons', 'studios'));
    }
}
