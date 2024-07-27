<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index(Category $category, Request $request)
    {
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        $states = $category->states()
                    ->orderBy($sort, $direction)
                    ->paginate(10);

        return view('admin.states.index', compact('category', 'states'));
    }

    public function create(Category $category)
    {
        return view('admin.states.create', compact('category'));
    }

    public function store(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:states|max:255',
        ]);

        $state = $category->states()->create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
        ]);

        return redirect()->route('admin.states.index', $category)
                         ->with('success', 'State created successfully.');
    }

    public function show(Category $category, State $state)
    {
        return view('admin.states.show', compact('category', 'state'));
    }

    public function edit(Category $category, State $state)
    {
        $categories = Category::all(); // Add this line
        return view('admin.states.edit', compact('category', 'state', 'categories'));
    }

    public function update(Request $request, Category $category, State $state)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:states,name,' . $state->id . '|max:255',
            'category_id' => 'required|exists:categories,id',
            'content_header' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $state->update([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'category_id' => $validatedData['category_id'],
            'content_header' => $validatedData['content_header'],
            'content' => $validatedData['content'],
        ]);

        $newCategory = Category::find($validatedData['category_id']);
        
        return redirect()->route('state.show', [
            'category' => $newCategory->slug,
            'state' => $state->slug
        ])->with('success', 'State updated successfully.');
    }

    public function destroy(Category $category, State $state)
    {
        $state->delete();
        return redirect()->route('admin.states.index', $category)
                         ->with('success', 'State deleted successfully.');
    }
}