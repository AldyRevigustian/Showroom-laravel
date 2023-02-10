<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FetchAllRoom;
use App\Http\Controllers\API\LiveController;
use App\Http\Controllers\API\ProfileController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('rooms')->controller(FetchAllRoom::class)->group(function () {
    Route::get('/', 'rooms');
    Route::get('/onlives', 'onLives');
    Route::get('/profile/{room_id}', 'profile');
    Route::get('/next_live/{room_id}', 'nextLive');
    Route::get('/total_rank/{room_id}', 'totalRank');
    Route::get('/fan_letter/{room_id}', 'fanLetter');
});

Route::prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get('/{user_id}', 'index');
});

Route::prefix('live')->controller(LiveController::class)->group(function () {
    Route::post('/comment', 'send_comment');
    Route::get('/comment_log/{room_id}', 'comment_log');
    Route::get('/gift_log/{room_id}', 'gift_log');
    Route::get('/stage_user_list/{room_id}', 'stage_user_list');

});
