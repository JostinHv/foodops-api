<?php

use App\Http\Controllers\Api\CategoriaMenuController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'categorias-menu'
], function () {
    Route::get('/', [CategoriaMenuController::class, 'index']);
    Route::post('/', [CategoriaMenuController::class, 'store']);
    Route::get('/{id}', [CategoriaMenuController::class, 'show']);
    Route::put('/{id}', [CategoriaMenuController::class, 'update']);
    Route::delete('/{id}', [CategoriaMenuController::class, 'destroy']);
    Route::patch('/cambiar-estado', [CategoriaMenuController::class, 'cambiarEstadoAutomatico']);
});
