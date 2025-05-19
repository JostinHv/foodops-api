<?php

use App\Http\Controllers\Api\MetodoPagoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'metodos-pago'
], function () {
    Route::get('/', [MetodoPagoController::class, 'index']);
    Route::post('/', [MetodoPagoController::class, 'store']);
    Route::get('/{id}', [MetodoPagoController::class, 'show']);
    Route::put('/{id}', [MetodoPagoController::class, 'update']);
    Route::delete('/{id}', [MetodoPagoController::class, 'destroy']);
    Route::patch('/{id}/cambiar-estado', [MetodoPagoController::class, 'cambiarEstadoAutomatico']);
});
