<?php

use App\Http\Controllers\Api\EstadoOrdenController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'estados-orden'
], function () {
    Route::get('/', [EstadoOrdenController::class, 'index']);
    Route::post('/', [EstadoOrdenController::class, 'store']);
    Route::get('/{id}', [EstadoOrdenController::class, 'show']);
    Route::put('/{id}', [EstadoOrdenController::class, 'update']);
    Route::delete('/{id}', [EstadoOrdenController::class, 'destroy']);
    Route::patch('/{id}/cambiar-estado', [EstadoOrdenController::class, 'cambiarEstadoAutomatico']);
});
