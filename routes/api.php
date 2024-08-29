<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    // User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'update']);

    // Banner
    Route::resource('banners', BannerController::class);
    
    // Blog
    Route::resource('blogs', BlogController::class);
    
    // Portfolio
    Route::resource('portfolios', BlogController::class);
    
    // Comment
    Route::resource('comments', CommentController::class);

    Route::get('/leaderboard', [LeaderboardController::class, 'leaderboard_get']);
    Route::post('/leaderboard', [LeaderboardController::class, 'leaderboard_post']);

});
