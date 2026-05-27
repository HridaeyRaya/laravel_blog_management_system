<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Task 1.4
Route::get('/lifecycle-test', function () {
    return response()->json([
        'php_version' => phpversion(),
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Suspended route
Route::get('/suspended', function () {
    return view('suspended');
})->name('suspended');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//Admin Routes
Route::middleware(['auth', 'active'])->prefix('admin')->group(function () {
    Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
    Route::put('/admin/permissions/{role}', [PermissionController::class, 'update'])->name('admin.permissions.update');

    // New user management routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleSuspend'])->name('admin.users.toggle');
});

// Post Routes
Route::middleware(['auth', 'active', 'permission'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::patch('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
});

Route::resource('posts', PostController::class)->only(['index', 'show']);

// Comment Routes
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

