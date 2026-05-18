<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';
    
    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'content', 
        'image_path', 'published_at', 'is_published'
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where(function ($q) {
                        // Jika published_at null, anggap langsung tayang
                        $q->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                    })
                    ->orderByDesc('published_at');
    }
}