<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('category')->get();
        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.states.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:states|max:255',
        ]);

        State::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.states.index')->with('success', 'State created successfully.');
    }

    public function show($category, State $state)
    {
        return view('admin.states.show', compact('category', 'state'));
    }

    public function edit($category, State $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        return view('admin.states.edit', compact('category', 'state'));
    }

    public function update(Request $request, $category, State $state)
    {
        $request->validate([
            'name' => 'required|unique:states,name,' . $state->id . '|max:255',
        ]);

        $state->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.states.index')->with('success', 'State updated successfully.');
    }

    public function destroy($category, State $state)
    {
        $state->delete();
        return redirect()->route('admin.states.index')->with('success', 'State deleted successfully.');
    }
}