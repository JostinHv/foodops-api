<?php

use App\Http\Controllers\Api\HistorialPagoSuscripcionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'historial-pagos'
], function () {
    Route::get('/', [HistorialPagoSuscripcionController::class, 'index']);
    Route::post('/', [HistorialPagoSuscripcionController::class, 'store']);
    Route::get('/{id}', [HistorialPagoSuscripcionController::class, 'show']);
    Route::put('/{id}', [HistorialPagoSuscripcionController::class, 'update']);
    Route::delete('/{id}', [HistorialPagoSuscripcionController::class, 'destroy']);
});
