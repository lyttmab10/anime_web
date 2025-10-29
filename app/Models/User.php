<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    
    // Relationship with Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // Relationship with Watchlists
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
    
    // Relationship with UserRelationships (users that this user is related to)
    public function relationships()
    {
        return $this->hasMany(UserRelationship::class, 'user_id');
    }
    
    // Relationship with users that this user is following
    public function following()
    {
        return $this->hasMany(UserRelationship::class, 'user_id')
                    ->where('type', 'follow')
                    ->where('status', 'accepted');
    }
    
    // Relationship with followers
    public function followers()
    {
        return $this->hasMany(UserRelationship::class, 'related_user_id')
                    ->where('type', 'follow')
                    ->where('status', 'accepted');
    }
    
    // Relationship with friends
    public function friends()
    {
        return $this->hasMany(UserRelationship::class, 'user_id')
                    ->where('type', 'friend')
                    ->where('status', 'accepted');
    }
    
    // Relationship with users that have requested to follow this user
    public function followRequests()
    {
        return $this->hasMany(UserRelationship::class, 'related_user_id')
                    ->where('type', 'follow')
                    ->where('status', 'pending');
    }
}
