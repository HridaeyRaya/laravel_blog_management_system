<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Task 1.4
Route::get('/lifecycle-test', function (){
    return response () -> json([
        'php_version' => phpversion(),
        'timestamp' => now()->toDateTimeString(),
    ]);
});
