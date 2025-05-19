<?php

use App\Http\Controllers\Api\MesaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'mesas'
], function () {
    Route::get('/', [MesaController::class, 'index']);
    Route::post('/', [MesaController::class, 'store']);
    Route::get('/{id}', [MesaController::class, 'show']);
    Route::put('/{id}', [MesaController::class, 'update']);
    Route::delete('/{id}', [MesaController::class, 'destroy']);
});
