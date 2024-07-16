<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name', 'slug', 'subcategory_id'];

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
