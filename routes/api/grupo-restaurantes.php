<?php

use App\Http\Controllers\Api\GrupoRestaurantesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'grupo-restaurantes'
], function () {
    Route::get('/', [GrupoRestaurantesController::class, 'index']);
    Route::post('/', [GrupoRestaurantesController::class, 'store']);
    Route::get('/{id}', [GrupoRestaurantesController::class, 'show']);
    Route::put('/{id}', [GrupoRestaurantesController::class, 'update']);
    Route::delete('/{id}', [GrupoRestaurantesController::class, 'destroy']);
});
