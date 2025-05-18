<?php

use App\Http\Controllers\API\CategoriaMenuController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'categorias'], function () {
    Route::get('/', [CategoriaMenuController::class, 'index']);
    Route::get('/{id}', [CategoriaMenuController::class, 'show']);
    Route::post('/', [CategoriaMenuController::class, 'store']);
    Route::put('/{id}', [CategoriaMenuController::class, 'update']);
    Route::delete('/{id}', [CategoriaMenuController::class, 'destroy']);
    Route::get('/activos', [CategoriaMenuController::class, 'activos']);
    Route::get('/ultimo-activo', [CategoriaMenuController::class, 'ultimoActivo']);
    Route::post('/{id}/estado', [CategoriaMenuController::class, 'cambiarEstado']);
});
