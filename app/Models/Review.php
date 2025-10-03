<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'anime_id',
        'rating',
        'review',
        'likes',
        'dislikes',
    ];
    
    protected $casts = [
        'rating' => 'integer',
        'likes' => 'integer',
        'dislikes' => 'integer',
    ];
    
    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship with Anime
    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
