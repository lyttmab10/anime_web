<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;

class AnimeController extends Controller
{
    public function index()
    {
        // Check if we're on the home page or the all anime page
        $isHomePage = request()->routeIs('home');
        
        if ($isHomePage) {
            // Get one random anime for "Anime of the Day"
            $featuredAnime = Anime::inRandomOrder()->first();
            
            // Get only trending anime for the grid (20 items)
            $trendingAndRecentAnimes = Anime::where('is_trending', true)
                ->orderByRaw("CASE 
                    WHEN title = 'ดาบพิฆาตอสูร' THEN 1
                    WHEN title = 'โจมตียักษ์' THEN 2
                    ELSE 3
                END")
                ->orderBy('rating', 'desc')
                ->limit(20)
                ->get();
            
            // Get 30 anime listings for the new section
            $animeListings = Anime::orderBy('rating', 'desc')->limit(30)->get();
            
            // Get all unique genres for the category section
            $allGenres = Anime::select('genres')
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

            return view('anime.home', compact('featuredAnime', 'trendingAndRecentAnimes', 'animeListings', 'allGenres'));
        } else {
            // For the all anime page, just return all anime ordered by rating
            $animes = Anime::orderBy('rating', 'desc')->paginate(24);
            
            return view('anime.all', compact('animes'));
        }
    }
    
    // Add a method to handle anime creation with image upload
    public function create()
    {
        return view('anime.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'nullable|date',
            'rating' => 'nullable|numeric|min:0|max:10',
            'is_trending' => 'boolean',
            'studio' => 'nullable|string|max:255',
            'episodes' => 'nullable|integer',
            'genres' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $anime = new Anime($request->except('image'));
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('anime', 'public');
            $anime->image_url = '/storage/' . $imagePath;
        }
        
        $anime->save();
        
        return redirect()->route('home')->with('success', 'Anime created successfully!');
    }
    
    public function show(Anime $anime)
    {
        // Load anime with reviews and users
        $anime = Anime::with(['reviews' => function($query) {
            $query->with('user')->latest();
        }])->where('id', $anime->id)->first();
        
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
        ]);
        
        $animeIds = [$request->anime1_id, $request->anime2_id];
        
        $animes = Anime::whereIn('id', $animeIds)->get();
        
        return view('anime.compare', compact('animes'));
    }
}