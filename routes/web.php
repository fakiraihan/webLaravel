<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('home');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Admin routes (require authentication and admin role)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/articles/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/articles', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/articles/{article}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/articles/{article}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/articles/{article}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// VULN: IDOR - delete comment by ID, no auth check
Route::get('/comments/delete/{id}', function($id) {
    \App\Models\Comment::destroy($id);
    return back()->with('success', 'Comment deleted (no check).');
});
