<?php

use App\Http\Controllers\Api\AsignacionPersonalController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'asignaciones-personal'
], function () {
    Route::get('/', [AsignacionPersonalController::class, 'index']);
    Route::post('/', [AsignacionPersonalController::class, 'store']);
    Route::get('/{id}', [AsignacionPersonalController::class, 'show']);
    Route::put('/{id}', [AsignacionPersonalController::class, 'update']);
    Route::delete('/{id}', [AsignacionPersonalController::class, 'destroy']);
    Route::post('/cambiar-estado', [AsignacionPersonalController::class, 'cambiarEstadoAutomatico']);
});
