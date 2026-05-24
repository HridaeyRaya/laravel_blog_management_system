<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Task 1.4
Route::get('/lifecycle-test', function () {
    return response()->json([
        'php_version' => phpversion(),
        'timestamp'   => now()->toIso8601String(),
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

// Post Routes
Route::resource('posts', PostController::class)->only(['index', 'show']);

Route::middleware(['auth', 'active'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});
