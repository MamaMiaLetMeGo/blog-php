<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function index($category, $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();

        $forms = Form::where('category_id', $category->id)
                    ->where('state_id', $state->id)
                    ->get();

        return view('forms.index', compact('category', 'state', 'forms'));
    }

    public function category($category)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        return view('category.show', compact('category'));
    }

    public function state($category, $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();
        $forms = $state->forms()->where('category_id', $category->id)->get();

        return view('state.show', compact('category', 'state', 'forms'));
    }

    public function edit(Form $form)
    {
        $categories = Category::all();
        $states = State::all();
    
        $category = $form->category;
        $state = $form->state;
    
        return view('admin.forms.edit', compact('form', 'categories', 'states', 'category', 'state'));
    }

    public function update(Request $request, Form $form)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'state_id' => 'required|exists:states,id',
            'content_header' => 'nullable',
            'content' => 'nullable',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($form->file_path) {
                \Storage::disk('public')->delete($form->file_path);
            }
            // Store new file
            $validatedData['file_path'] = $request->file('file')->store('forms', 'public');
        }

        $form->update($validatedData);

        // Update slug if name has changed
        if ($form->isDirty('name')) {
            $form->slug = Str::slug($form->name);
            $form->save();
        }

        return redirect()->route('admin.forms.show', $form)
                 ->with('success', 'Form updated successfully');
    }

    public function show(Form $form)
    {
        $category = $form->category;
        $state = $form->state;

        return view('admin.forms.show', compact('form', 'category', 'state'));
    }

    public function publicShow($category, $state, $form)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();
        $form = Form::where('slug', $form)
                    ->where('category_id', $category->id)
                    ->where('state_id', $state->id)
                    ->firstOrFail();

        return view('forms.show', compact('category', 'state', 'form'));
    }

    public function create()
    {
        $categories = Category::all();
        $states = State::all();

        return view('admin.forms.create', compact('categories', 'states'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'state_id' => 'required|exists:states,id',
            'content_header' => 'nullable',
            'content' => 'nullable',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['file_path'] = $request->file('file')->store('forms', 'public');

        $form = Form::create($validatedData);

        return redirect()->route('admin.forms.show', $form)
                 ->with('success', 'Form created successfully');
    }

    public function download($category, $state, $form)
    {
        $form = Form::where('slug', $form)
                    ->whereHas('category', function ($query) use ($category) {
                        $query->where('slug', $category);
                    })
                    ->whereHas('state', function ($query) use ($state) {
                        $query->where('slug', $state);
                    })
                    ->firstOrFail();

        $form->increment('downloads');
        
        return response()->download(storage_path('app/public/' . $form->file_path));
    }
}