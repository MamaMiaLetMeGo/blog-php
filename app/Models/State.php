<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name', 'slug', 'category_id'];

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
