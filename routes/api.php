<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
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

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function (): void {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        Route::post('/products/{product}/images', [ProductController::class, 'storeImages']);
        Route::delete('/products/{product}/images/{image}', [ProductController::class, 'destroyImage']);
        Route::patch('/products/{product}/images/{image}/primary', [ProductController::class, 'setPrimaryImage']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::any('/admin/{path?}', function (Request $request): array {
            return [
                'success' => true,
                'message' => 'Admin route access granted.',
                'method' => $request->method(),
                'path' => $request->path(),
            ];
        })->where('path', '.*');
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel']);

        Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
        Route::post('/payments/confirm', [PaymentController::class, 'confirm']);

        Route::get('/cart', [CartController::class, 'show']);
        Route::post('/cart/items', [CartController::class, 'addItem']);
        Route::put('/cart/items/{item}', [CartController::class, 'updateItem']);
        Route::delete('/cart/items/{item}', [CartController::class, 'removeItem']);
    });

    Route::post('/payments/webhook', [PaymentController::class, 'webhook']);
});
