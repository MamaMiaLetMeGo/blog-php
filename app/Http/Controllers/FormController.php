<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\State;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function index($category, $subcategory, $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();

        $forms = Form::where('category_id', $category->id)
                    ->where('subcategory_id', $subcategory->id)
                    ->where('state_id', $state->id)
                    ->get();

        return view('forms.index', compact('category', 'subcategory', 'state', 'forms'));
    }

    public function category($category)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategories = $category->subcategories;

        return view('category.show', compact('category', 'subcategories'));
    }

    public function subcategory($category, $subcategory)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        $states = $subcategory->states;

        return view('subcategory.show', compact('category', 'subcategory', 'states'));
    }

    public function state($category, $subcategory, $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();
        $forms = $state->forms()->where('category_id', $category->id)
                                ->where('subcategory_id', $subcategory->id)
                                ->get();

        return view('state.show', compact('category', 'subcategory', 'state', 'forms'));
    }

    public function editCategory($category)
    {
        $category = Category::where('slug', $category)->firstOrFail();

        return view('category.edit', compact('category'));
    }

    public function editSubcategory($category, $subcategory)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();

        return view('subcategory.edit', compact('category', 'subcategory'));
    }

    public function editState($category, $subcategory, $state)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();

        return view('state.edit', compact('category', 'subcategory', 'state'));
    }

    public function updateCategory(Request $request, $category)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            // Add other fields as needed
        ]);

        $category->update($validatedData);

        return redirect()->route('category.show', $category->slug)->with('success', 'Category updated successfully');
    }

    public function updateSubcategory(Request $request, $category, $subcategory)
    {
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            // Add other fields as needed
        ]);

        $subcategory->update($validatedData);

        return redirect()->route('subcategory.show', [$category, $subcategory->slug])->with('success', 'Subcategory updated successfully');
    }

    public function updateState(Request $request, $category, $subcategory, $state)
    {
        $state = State::where('slug', $state)->firstOrFail();
        
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            // Add other fields as needed
        ]);

        $state->update($validatedData);

        return redirect()->route('state.show', [$category, $subcategory, $state->slug])->with('success', 'State updated successfully');
    }

    public function edit(Form $form)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $states = State::all();
    
        $category = $form->category;
        $subcategory = $form->subcategory;
        $state = $form->state;
    
        return view('admin.forms.edit', compact('form', 'categories', 'subcategories', 'states', 'category', 'subcategory', 'state'));
    }

    public function update(Request $request, Form $form)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'state_id' => 'required|exists:states,id',
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
        $subcategory = $form->subcategory;
        $state = $form->state;

        return view('admin.forms.show', compact('form', 'category', 'subcategory', 'state'));
    }

    public function publicShow($category, $subcategory, $state, $form)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
        $state = State::where('slug', $state)->firstOrFail();
        $form = Form::where('slug', $form)
                    ->where('category_id', $category->id)
                    ->where('subcategory_id', $subcategory->id)
                    ->where('state_id', $state->id)
                    ->firstOrFail();

        return view('admin.forms.show', compact('category', 'subcategory', 'state', 'form'));
    }
}