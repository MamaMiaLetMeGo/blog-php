<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads', $filename);
            return response()->json(['location' => asset('storage/uploads/' . $filename)]);
        }
        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}