public function boot()
{
    // ...

    Route::bind('category', function ($value) {
        try {
            return \App\Models\Category::where('slug', $value)->firstOrFail();
        } catch (\Exception $e) {
            \Log::error("Failed to find category with slug: {$value}");
            abort(404, 'Category not found');
        }
    });

    Route::bind('state', function ($value) {
        try {
            return \App\Models\State::where('slug', $value)->firstOrFail();
        } catch (\Exception $e) {
            \Log::error("Failed to find state with slug: {$value}");
            abort(404, 'State not found');
        }
    });

    // ...
}