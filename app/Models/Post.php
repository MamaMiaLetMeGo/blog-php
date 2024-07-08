<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'content', 'thumbnail', 'meta_description', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function readTime()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        return $minutes;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
