<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function () {
    //user
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Route Post
    Route::get('posts', [PostController::class, 'index']); // All post
    Route::post('posts', [PostController::class, 'store']); // Create post
    Route::get('posts/{id}', [PostController::class, 'show']); // Get single post
    Route::put('posts/{id}', [PostController::class, 'update']); // edit post
    Route::delete('posts/{id}', [PostController::class, 'destroy']); // delete post

    //Route Comment
    Route::get('posts/{id}/comments', [CommentController::class, 'index']); // All comment of a post
    Route::post('posts/{id}/comments', [CommentController::class, 'store']); // Create comment on a post
    Route::put('comments/{id}', [CommentController::class, 'update']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);

    //Like
    Route::post('posts/{id}/likes', [LikeController::class, 'likeOrUnlike']); // Like Or Unlike
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
