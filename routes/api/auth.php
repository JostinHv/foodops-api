<?php

use App\Http\Controllers\Api\AutenticacionController;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function () {
    Route::middleware('auth:api')->prefix('v1')->group(function () {
        Route::post('/logout', [AutenticacionController::class, 'logout']);
        Route::get('/me', [AutenticacionController::class, 'me']);
    });
    Route::middleware('throttle')->group(function () {
        Route::post('/register', [AutenticacionController::class, 'register']);
        Route::post('/login', [AutenticacionController::class, 'login']);
        Route::post('/refresh', [AutenticacionController::class, 'refresh']);
    });
    Route::get('/comprobar-email/{email}', [AutenticacionController::class, 'comprobarEmail']);
    Route::post('/autenticacion', [AutenticacionController::class, 'autenticarse']);
});
