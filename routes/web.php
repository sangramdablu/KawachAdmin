<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    if(!auth()->check()){
        return redirect()->route('login');
    }else{
        return view('dashboard.dashboard');
    }
});

Route::get('/blog', function () {
    return view('blogs.create');
})->name('blog');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Guest Routes (Only for NOT logged in users)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register-form', [AuthController::class, 'registerForm'])->name('register-form');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Admin Panel)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth' , 'admin'])->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    // Option A — resource routes (most common)
    Route::resource('blogs', BlogController::class);

    // Custom edit route (optional)
    // Route::get('/blogs/{slug}/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit.page');

    // Autosave (protected)
    Route::post('/blogs/fhy6adv645gv5zd5', [BlogController::class, 'autosave'])->name('blogs.fhy6adv645gv5zd5');
    Route::post('/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload.image');

});