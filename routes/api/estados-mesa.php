<?php

use App\Http\Controllers\Api\EstadoMesaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'estados-mesa'
], function () {
    Route::get('/', [EstadoMesaController::class, 'index']);
    Route::post('/', [EstadoMesaController::class, 'store']);
    Route::get('/{id}', [EstadoMesaController::class, 'show']);
    Route::put('/{id}', [EstadoMesaController::class, 'update']);
    Route::delete('/{id}', [EstadoMesaController::class, 'destroy']);
    Route::patch('/{id}/cambiar-estado', [EstadoMesaController::class, 'cambiarEstadoAutomatico']);
});
