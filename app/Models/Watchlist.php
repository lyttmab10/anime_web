<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $fillable = [
        'user_id',
        'anime_id',
        'status',
        'progress',
        'notes',
    ];
    
    protected $casts = [
        'progress' => 'integer',
        'status' => 'string',
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
