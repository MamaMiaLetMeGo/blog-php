<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Form;
use App\Models\Category;
use App\Models\State;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Sorting parameters
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        // Posts
        $posts = Post::orderBy($sort, $direction)->paginate(10);

        // Forms
        $forms = Form::orderBy($sort, $direction)->paginate(10);

        // Categories
        $categories = Category::orderBy($sort, $direction)->paginate(10);

        // States
        $states = State::orderBy($sort, $direction)->paginate(10);

        return view('dashboard', compact('posts', 'forms', 'categories', 'states'));
    }
}