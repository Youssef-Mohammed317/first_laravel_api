<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/add/post',[PostController::class, 'addNewPost']);
    // first method for send id of the post is in form request data
    Route::post('/edit/post',[PostController::class, 'editPost']);
    // seconde method for send id of the post is in url
    Route::post('/edit2/post/{id}',[PostController::class, 'editPost2']);

    Route::delete('/delete/post/{id}',[PostController::class, 'deletePost']);
});

Route::get('/posts', [PostController::class, 'getPosts']);
Route::get('/post/{id}', [PostController::class, 'getPost']);

