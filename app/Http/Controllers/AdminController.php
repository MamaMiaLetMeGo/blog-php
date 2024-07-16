<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $forms = Form::with(['category', 'state'])->get();
        return view('admin.forms.index', compact('forms'));
    }

    public function createForm()
    {
        $categories = Category::all();
        $states = State::all();

        return view('admin.create-form', compact('categories', 'states'));
    }

    public function storeForm(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'state_id' => 'required|exists:states,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'content' => 'nullable',
        ]);

        $filePath = $request->file('file')->store('forms', 'public');

        Form::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'state_id' => $request->state_id,
            'file_path' => $filePath,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.forms.index')->with('success', 'Form created successfully.');
    }

    public function editForm(Form $form)
    {
        $categories = Category::all();
        $states = State::all();

        return view('admin.forms.edit', compact('form', 'categories', 'states'));
    }

    public function updateForm(Request $request, Form $form)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'state_id' => 'required|exists:states,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'content' => 'nullable',
        ]);

        $form->name = $request->name;
        $form->slug = Str::slug($request->name);
        $form->category_id = $request->category_id;
        $form->state_id = $request->state_id;
        $form->content = $request->content;

        if ($request->hasFile('file')) {
            // Delete old file
            if ($form->file_path) {
                \Storage::disk('public')->delete($form->file_path);
            }
            // Store new file
            $filePath = $request->file('file')->store('forms', 'public');
            $form->file_path = $filePath;
        }

        $form->save();

        return redirect()->route('admin.forms.index')->with('success', 'Form updated successfully.');
    }

    public function destroyForm(Form $form)
    {
        if ($form->file_path) {
            \Storage::disk('public')->delete($form->file_path);
        }
        $form->delete();

        return redirect()->route('admin.forms.index')->with('success', 'Form deleted successfully.');
    }
}