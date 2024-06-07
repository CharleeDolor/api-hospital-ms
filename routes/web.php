<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

// // route to login form and logging in
// Route::get('/user', [UserController::class, 'loginForm'])->name('user.login');

// //auth controller routes for login and logout
// Route::post('login', [AuthController::class, 'doLogin'])->name('auth.doLogin');
// Route::post('logout', [AuthController::class, 'doLogout'])->name('auth.doLogout')->middleware('auth:sanctum');

// // routes for creating user
// Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
// Route::post('/user', [UserController::class, 'store'])->name('user.store');

// // route to forbidden
// Route::get('/users/forbidden', [UserController::class, 'goForbidden'])->name('user.forbidden');

// // routes for posts crud
// Route::get('/posts', [PostController::class, 'index'])->name('posts.home')->middleware('auth:sanctum');
// Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth:sanctum');
// Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth:sanctum');
// Route::get('/posts/show/{id}', [PostController::class, 'show'])->name('posts.show')->middleware('auth:sanctum');
// Route::get('/posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth:sanctum');
// Route::put('/posts/update/{id}', [PostController::class, 'update'])->name('posts.update')->middleware('auth:sanctum');
// Route::delete('/posts/delete/{id}', [PostController::class, 'destroy'])->name('posts.delete')->middleware('auth:sanctum');


