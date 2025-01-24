<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/add/post',[PostController::class, 'addNewPost']);
    // first method for send id of the post is in form request data
    Route::put('/edit/post',[PostController::class, 'editPost']);
    // seconde method for send id of the post is in url
    Route::put('/edit2/post/{id}',[PostController::class, 'editPost2']);

    Route::delete('/delete/post/{id}',[PostController::class, 'deletePost']);

    Route::post('/add/comment',[CommentController::class, 'addNewComment']);
    Route::put('/edit/comment/{id}',[CommentController::class, 'editComment']);
    Route::delete('/delete/comment/{id}',[CommentController::class, 'deleteComment']);

    Route::post('/like/post/{id}',[LikeController::class, 'likePost']);
    Route::delete('/unlike/post/{id}',[LikeController::class, 'unlikePost']);

});

Route::get('/posts', [PostController::class, 'getPosts']);
Route::get('/post/{id}', [PostController::class, 'getPost']);

