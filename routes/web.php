<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\StateController;

use App\Models\Post;
use Illuminate\Support\Facades\Route;

// Auth routes (make sure this is at the top)
require __DIR__.'/auth.php';

// Home route
Route::get('/', function () {
    $recentPosts = Post::with('user')->latest()->take(5)->get();
    return view('homepage', compact('recentPosts'));
})->name('home');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public post routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// CKEditor image upload
Route::get('ckeditor', [CkeditorController::class, 'index']);
Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User's posts routes
    Route::get('/my-posts', [PostController::class, 'userPosts'])->name('user.posts.index');
    Route::get('/my-posts/create', [PostController::class, 'create'])->name('user.posts.create');
    Route::post('/my-posts', [PostController::class, 'store'])->name('user.posts.store');
    Route::get('/my-posts/{post}/edit', [PostController::class, 'edit'])->name('user.posts.edit');
    Route::put('/my-posts/{post}', [PostController::class, 'update'])->name('user.posts.update');
    Route::delete('/my-posts/{post}', [PostController::class, 'destroy'])->name('user.posts.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin post routes
    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts', [PostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/admin/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');

    // Admin form routes
    Route::get('/admin/forms', [AdminController::class, 'index'])->name('admin.forms.index');
    Route::get('/admin/forms/create', [AdminController::class, 'createForm'])->name('admin.forms.create');
    Route::post('/admin/forms', [AdminController::class, 'storeForm'])->name('admin.forms.store');
    Route::get('/admin/forms/{form}/edit', [FormController::class, 'edit'])->name('admin.forms.edit');
    Route::put('/admin/forms/{form}', [FormController::class, 'update'])->name('admin.forms.update');
    Route::get('/admin/forms/{form}', [FormController::class, 'show'])->name('admin.forms.show');
    Route::delete('/admin/forms/{form}', [AdminController::class, 'destroyForm'])->name('admin.forms.destroy');

    // Admin state routes
    Route::group(['prefix' => 'admin'], function () {
        Route::get('states', [StateController::class, 'index'])->name('admin.states.index');
        Route::get('states/create', [StateController::class, 'create'])->name('admin.states.create');
        Route::post('states', [StateController::class, 'store'])->name('admin.states.store');
        Route::get('states/{category}/{subcategory}/{state}', [StateController::class, 'show'])->name('admin.states.show');
        Route::get('states/{category}/{subcategory}/{state}/edit', [StateController::class, 'edit'])->name('admin.states.edit');
        Route::put('states/{category}/{subcategory}/{state}', [StateController::class, 'update'])->name('admin.states.update');
        Route::delete('states/{category}/{subcategory}/{state}', [StateController::class, 'destroy'])->name('admin.states.destroy');
    });

    // Admin category routes
    Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('admin.categories.show');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Admin subcategory routes
    Route::get('subcategories', [SubcategoryController::class, 'index'])->name('admin.subcategories.index');
    Route::get('subcategories/create', [SubcategoryController::class, 'create'])->name('admin.subcategories.create');
    Route::post('subcategories', [SubcategoryController::class, 'store'])->name('admin.subcategories.store');
    Route::get('subcategories/{category}/{subcategory}', [SubcategoryController::class, 'show'])->name('admin.subcategories.show');
    Route::get('subcategories/{category}/{subcategory}/edit', [SubcategoryController::class, 'edit'])->name('admin.subcategories.edit');
    Route::put('subcategories/{category}/{subcategory}', [SubcategoryController::class, 'update'])->name('admin.subcategories.update');
    Route::delete('subcategories/{category}/{subcategory}', [SubcategoryController::class, 'destroy'])->name('admin.subcategories.destroy');
});

// Public routes for viewing categories, subcategories, states, and forms
Route::get('/{category}', [FormController::class, 'category'])->name('category.show');
Route::get('/{category}/{subcategory}', [FormController::class, 'subcategory'])->name('subcategory.show');
Route::get('/{category}/{subcategory}/{state}', [FormController::class, 'state'])->name('state.show');
Route::get('/{category}/{subcategory}/{state}/forms', [FormController::class, 'index'])->name('forms.index');
Route::get('/{category}/{subcategory}/{state}/{form}', [FormController::class, 'publicShow'])
    ->name('forms.show')
    ->where([
        'category' => '[a-z0-9-]+',
        'subcategory' => '[a-z0-9-]+',
        'state' => '[a-z0-9-]+',
        'form' => '[a-z0-9-]+'
    ]);