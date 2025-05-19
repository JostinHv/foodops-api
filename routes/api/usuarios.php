<?php

use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'usuarios'
], function () {
    Route::get('/', [UsuarioController::class, 'index']);
    Route::post('/', [UsuarioController::class, 'store']);
    Route::get('/{id}', [UsuarioController::class, 'show']);
    Route::put('/{id}', [UsuarioController::class, 'update']);
    Route::delete('/{id}', [UsuarioController::class, 'destroy']);
    Route::patch('/{id}/cambiar-estado', [UsuarioController::class, 'cambiarEstadoAutomatico']);
});
