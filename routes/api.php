<?php

use App\Http\Controllers\RedisTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Redis 測試 API 路由 - 需要 Sanctum 驗證
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/redis/test', [RedisTestController::class, 'testRedis']);
    Route::match(['GET', 'DELETE'], '/redis/clear', [RedisTestController::class, 'clearRedis']);
});

