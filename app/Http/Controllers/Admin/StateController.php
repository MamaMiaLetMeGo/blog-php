<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        $states = State::with('category')
                    ->orderBy($sort, $direction)
                    ->paginate(10);

        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        return view('admin.states.create');
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

    public function show(State $state)
    {
        return view('admin.states.show', compact('state'));
    }

    public function edit(State $state)
    {
        $categories = Category::all();
        return view('admin.states.edit', compact('state', 'categories'));
    }

    public function update(Request $request, State $state)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:states,name,' . $state->id . '|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $state->update([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'category_id' => $validatedData['category_id'],
        ]);

        return redirect()->route('admin.states.index')->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        $state->delete();
        return redirect()->route('admin.states.index')->with('success', 'State deleted successfully.');
    }
}