<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewUserInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'review_id',
        'interaction_type',
    ];

    protected $casts = [
        'interaction_type' => 'string',
    ];

    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Review
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
