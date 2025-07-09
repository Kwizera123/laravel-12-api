<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

 // Get all posts no need to authanticate
    Route::get('/all/posts', [PostController::class,'getAllPosts']);
    Route::get('/single/post/{post_id}', [PostController::class,'getPost']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);

    // Blog api endpoint starts here!!
    Route::post('/add/post', [PostController::class,'addNewPost']);
     // Edit Post api approach
     Route::post('/edit/post', [PostController::class,'editPost']);
          // Edit Post api with id approach 2
     Route::post('/edit/post/{post_id}', [PostController::class,'editPost2']);
    
});
