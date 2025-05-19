<?php

use App\Http\Controllers\Api\ReservaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'reservas'
], static function () {
    Route::get('/', [ReservaController::class, 'index']);
    Route::post('/', [ReservaController::class, 'store']);
    Route::get('/{id}', [ReservaController::class, 'show']);
    Route::put('/{id}', [ReservaController::class, 'update']);
    Route::delete('/{id}', [ReservaController::class, 'destroy']);
});
