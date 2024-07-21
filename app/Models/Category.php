<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
