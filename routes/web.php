<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;

// VULN: All authentication routes disabled - NO LOGIN NEEDED!
// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);

// VULN: All routes are now public - NO AUTHENTICATION!
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

// VULN: Admin routes without any protection - BRUTAL!
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/articles/create', [AdminController::class, 'create'])->name('admin.create');
Route::post('/admin/articles', [AdminController::class, 'store'])->name('admin.store');
Route::get('/admin/articles/{article}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/admin/articles/{article}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/admin/articles/{article}', [AdminController::class, 'destroy'])->name('admin.destroy');

// VULN: IDOR - delete comment by ID, no auth check
Route::get('/comments/delete/{id}', function($id) {
    \App\Models\Comment::destroy($id);
    return back()->with('success', 'Comment deleted (no check).');
});

// VULN: Direct SQL injection endpoints for testing
Route::get('/search/{search}', function($search) {
    // DIRECT SQL INJECTION - NO SANITIZATION!
    $results = \DB::select("SELECT * FROM articles WHERE title LIKE '%$search%' OR content LIKE '%$search%'");
    return view('search_results', compact('results', 'search'));
});

Route::get('/user/{userId}', function($userId) {
    // DIRECT SQL INJECTION - NO SANITIZATION!
    $user = \DB::select("SELECT * FROM users WHERE id = $userId");
    if (!empty($user)) {
        $user = $user[0];
        echo "<h1>User Info (VULNERABLE!)</h1>";
        echo "<p>ID: " . $user->id . "</p>";
        echo "<p>Username: " . $user->username . "</p>";
        echo "<p>Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . "</p>";
    } else {
        echo "<h1>User not found</h1>";
    }
});

