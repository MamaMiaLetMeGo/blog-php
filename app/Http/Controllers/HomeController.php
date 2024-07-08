<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        return view('homepage', compact('recentPosts'));
    }
}