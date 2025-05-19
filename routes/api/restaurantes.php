<?php

use App\Http\Controllers\Api\RestauranteController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'restaurantes'
], function () {
    Route::get('/', [RestauranteController::class, 'index']);
    Route::post('/', [RestauranteController::class, 'store']);
    Route::get('/{id}', [RestauranteController::class, 'show']);
    Route::put('/{id}', [RestauranteController::class, 'update']);
    Route::delete('/{id}', [RestauranteController::class, 'destroy']);
});
