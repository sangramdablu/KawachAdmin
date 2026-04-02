<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;


Route::get('/', function () {
    return view('layouts.master');
});
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');

Route::get('/blog', function () {
    return view('blogs.create');
})->name('blog');

Route::get('/login', function () {
    return view('auth.login');
})->name('blog');

Route::resource('blogs', BlogController::class);
Route::get('/blogs/{slug}/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit.page');
Route::post('/blogs/fhy6adv645gv5zd5', [BlogController::class, 'autosave'])->name('blogs.fhy6adv645gv5zd5');
