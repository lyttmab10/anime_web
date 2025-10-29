<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'rating',
        'image_url',
        'studio',
        'season',
        'episodes',
        'genres',
        'characters',
        'trailer_url',
        'official_site',
        'status',
    ];

    protected $casts = [
        'release_date' => 'date',
        'episodes' => 'integer',
        'genres' => 'array',
        'characters' => 'array',
        'rating' => 'decimal:2',
    ];
    
    // Relationship with Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class)->with('user')->latest();
    }
    
    // Calculate average rating from reviews
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
    
    // Get similar anime based on genres
    public function getSimilarAnime($limit = 6)
    {
        if (!$this->genres || !is_array($this->genres)) {
            return collect();
        }
        
        return self::where('id', '!=', $this->id)
            ->where(function($query) {
                foreach ($this->genres as $genre) {
                    $query->orWhereJsonContains('genres', $genre);
                }
            })
            ->orderByRaw('CASE 
                WHEN rating >= 9.0 THEN 1
                ELSE 2
            END')
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }
    
    // Get recommended anime based on user's review history
    public static function getRecommendationsForUser($userId, $limit = 6)
    {
        // Get genres from user's reviews
        $userReviewedAnime = Review::where('user_id', $userId)
            ->with('anime')
            ->get()
            ->pluck('anime');
            
        if ($userReviewedAnime->isEmpty()) {
            // If no reviews from user, return trending anime
            return self::where('rating', '>=', 9.0)
                ->orderBy('rating', 'desc')
                ->limit($limit)
                ->get();
        }
        
        // Collect all genres from user's reviewed anime
        $userGenres = [];
        foreach ($userReviewedAnime as $anime) {
            if ($anime->genres && is_array($anime->genres)) {
                $userGenres = array_merge($userGenres, $anime->genres);
            }
        }
        
        $userGenres = array_unique($userGenres);
        
        // Find anime with similar genres that the user hasn't reviewed yet
        $reviewedAnimeIds = $userReviewedAnime->pluck('id')->toArray();
        
        return self::whereNotIn('id', $reviewedAnimeIds)
            ->where(function($query) use ($userGenres) {
                foreach ($userGenres as $genre) {
                    $query->orWhereJsonContains('genres', $genre);
                }
            })
            ->orderByRaw('CASE 
                WHEN rating >= 9.0 THEN 1
                ELSE 2
            END')
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }
    
    // Relationship with Watchlists
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
    
    // Dynamic accessor to determine if anime is trending based on rating
    public function getIsTrendingAttribute()
    {
        return $this->rating >= 9.0;
    }
}