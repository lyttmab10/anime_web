<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelationship extends Model
{
    protected $fillable = [
        'user_id',
        'related_user_id',
        'type',
        'status',
    ];
    
    protected $casts = [
        'type' => 'string',
        'status' => 'string',
    ];
    
    // Relationship with the user who initiated the relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship with the user who is the target of the relationship
    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }
}
