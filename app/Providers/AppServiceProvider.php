<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\State;
use App\Models\Form;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->bind('admin', AdminMiddleware::class);

        Route::bind('category', function ($value) {
            return Category::where('slug', $value)->first() ?? abort(404, 'Category not found');
        });

        Route::bind('state', function ($value, $route) {
            $category = $route->parameter('category');
            if (!$category) {
                return State::where('slug', $value)->first() ?? abort(404, 'State not found');
            }
            return State::where('slug', $value)
                        ->where('category_id', $category->id)
                        ->first() ?? abort(404, 'State not found');
        });

        Route::bind('form', function ($value, $route) {
            if (is_numeric($value)) {
                return Form::findOrFail($value);
            }
            
            $category = $route->parameter('category');
            $state = $route->parameter('state');
            
            if (!$category || !$state) {
                return Form::where('slug', $value)->firstOrFail();
            }
            
            return Form::where('slug', $value)
                        ->where('category_id', $category->id)
                        ->where('state_id', $state->id)
                        ->firstOrFail();
        });
    }
}