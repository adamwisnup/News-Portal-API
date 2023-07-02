<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// PostController
Route::controller(PostController::class)->group(function () {
  Route::get('/posts', 'index');
  Route::get('/posts/{id}', 'show');
});

//AuthController 
Route::controller(AuthController::class)->group(function () {
  Route::post('/register', 'register');
  Route::post('/login', 'login');
});

Route::middleware(['auth:sanctum'])->group(function () {
  //AuthController Middleware
  Route::get('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'me']);

  //PostController Middleware
  Route::controller(PostController::class)->group(function () {
    Route::post('/posts', 'store');
    Route::patch('/posts/{id}', 'update')->middleware('userPost');
    Route::delete('/posts/{id}', 'destroy')->middleware('userPost');
  });

  //CommentController Middleware
  Route::controller(CommentController::class)->group(function () {
    Route::post('/comments', 'store');
    Route::patch('/comments/{id}', 'update')->middleware('userComment');
    Route::delete('/comments/{id}', 'destroy')->middleware('userComment');
  });
});
