<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'content', 'thumbnail', 'meta_description', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
