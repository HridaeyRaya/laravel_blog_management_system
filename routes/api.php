<?php

use App\Http\Controllers\Api\PostController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Login
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = User::where('email', $request->email)->first();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ]
    ], 200);
});

// API Logout
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully'], 200);
});

// Public routes
Route::apiResource('posts', PostController::class)
    ->only(['index', 'show'])
    ->names([
        'index' => 'api.posts.index',
        'show'  => 'api.posts.show',
    ]);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)
        ->except(['index', 'show'])
        ->names([
            'store'   => 'api.posts.store',
            'update'  => 'api.posts.update',
            'destroy' => 'api.posts.destroy',
        ]);
});
