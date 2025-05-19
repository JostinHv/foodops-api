<?php

use App\Http\Controllers\Api\SucursalController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'sucursales'
], function () {
    Route::get('/', [SucursalController::class, 'index']);
    Route::post('/', [SucursalController::class, 'store']);
    Route::get('/{id}', [SucursalController::class, 'show']);
    Route::put('/{id}', [SucursalController::class, 'update']);
    Route::delete('/{id}', [SucursalController::class, 'destroy']);
});
