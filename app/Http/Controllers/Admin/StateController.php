<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('subcategory.category')->get();
        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.states.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:states|max:255',
            'subcategory_id' => 'required|exists:subcategories,id',
        ]);

        State::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'subcategory_id' => $request->subcategory_id,
        ]);

        return redirect()->route('admin.states.index')->with('success', 'State created successfully.');
    }

    public function show($category, $subcategory, State $state)
    {
        return view('admin.states.show', compact('category', 'subcategory', 'state'));
    }

    public function edit($category, $subcategory, State $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        return view('admin.states.edit', compact('category', 'subcategory', 'state'));
    }

    public function update(Request $request, $category, $subcategory, State $state)
    {
        $request->validate([
            'name' => 'required|unique:states,name,' . $state->id . '|max:255',
            'subcategory_id' => 'required|exists:subcategories,id',
        ]);

        $state->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'subcategory_id' => $request->subcategory_id,
        ]);

        return redirect()->route('admin.states.index')->with('success', 'State updated successfully.');
    }

    public function destroy($category, $subcategory, State $state)
    {
        $state->delete();
        return redirect()->route('admin.states.index')->with('success', 'State deleted successfully.');
    }
}