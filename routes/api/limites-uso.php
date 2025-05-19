<?php

use App\Http\Controllers\Api\LimiteUsoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'limites-uso'
], function () {
    Route::get('/', [LimiteUsoController::class, 'index']);
    Route::post('/', [LimiteUsoController::class, 'store']);
    Route::get('/{id}', [LimiteUsoController::class, 'show']);
    Route::put('/{id}', [LimiteUsoController::class, 'update']);
    Route::delete('/{id}', [LimiteUsoController::class, 'destroy']);
});
