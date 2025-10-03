<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'summary',
        'content',
        'image_url',
        'type',
        'is_published',
        'published_at',
        'author_name',
        'views',
        'tags',
    ];
    
    protected $casts = [
        'user_id' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
        'tags' => 'array',
    ];
    
    // Relationship with the user who authored the news
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Scope to get only published articles
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where('published_at', '<=', now());
    }
    
    // Scope to filter by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    // Mutator to automatically generate slug from title
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value ?: \Str::slug($this->title);
    }
    
    // Accessor to get formatted published date
    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y') : null;
    }
}
