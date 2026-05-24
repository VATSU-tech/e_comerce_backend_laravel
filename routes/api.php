<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', RegisterController::class);
        Route::post('/login', LoginController::class)->middleware('throttle:login');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('/logout', LogoutController::class);
            Route::get('/me', MeController::class);
        });
    });
});
