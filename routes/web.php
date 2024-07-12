<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CkeditorController;

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $recentPosts = Post::with('user')->latest()->take(5)->get();
    return view('homepage', compact('recentPosts'));
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public post routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

//ckeditor image upload
Route::get('ckeditor', [CkeditorController::class, 'index']);
Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

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

// Admin post routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts', [PostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/admin/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
});

require __DIR__.'/auth.php';