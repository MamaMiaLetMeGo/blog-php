<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class PublicCategoryController extends Controller
{
    public function show(Category $category)
    {
        $states = $category->states;
        return view('categories.show', compact('category', 'states'));
    }
}
