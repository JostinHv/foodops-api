<?php

use App\Http\Controllers\Api\ImagenController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'imagenes'
], function () {
    Route::get('/', [ImagenController::class, 'index']);
    Route::post('/', [ImagenController::class, 'store']);
    Route::get('/{id}', [ImagenController::class, 'show']);
    Route::put('/{id}', [ImagenController::class, 'update']);
    Route::delete('/{id}', [ImagenController::class, 'destroy']);
    Route::patch('/cambiar-estado', [ImagenController::class, 'cambiarEstadoAutomatico']);
});
