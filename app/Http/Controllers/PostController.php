<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('published_at', '<=', now())->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function adminIndex()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function userPosts()
    {
        $posts = auth()->user()->posts()->latest()->paginate(10);
        return view('posts.user-index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'meta_description' => 'nullable|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($request->title);
        $validatedData['published_at'] = now();

        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post = auth()->user()->posts()->create($validatedData);

        return redirect()->route('user.posts.index')->with('success', 'Post created successfully');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if (! Gate::allows('update', $post)) {
            abort(403);
        }
        
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (! Gate::allows('update', $post)) {
            abort(403);
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'meta_description' => 'nullable|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($request->title);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post->update($validatedData);

        return redirect()->route('user.posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        if (! Gate::allows('delete', $post)) {
            abort(403);
        }

        // Delete thumbnail if exists
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();
        return redirect()->route('user.posts.index')->with('success', 'Post deleted successfully');
    }
}