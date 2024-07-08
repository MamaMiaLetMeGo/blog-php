<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $posts = $user->posts()->latest()->paginate(10); // Get 10 posts per page
        return view('dashboard', compact('posts'));
    }
}
