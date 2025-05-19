<?php

use App\Http\Controllers\Api\IgvController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'igv'
], function () {
    Route::get('/', [IgvController::class, 'index']);
    Route::post('/', [IgvController::class, 'store']);
    Route::get('/{id}', [IgvController::class, 'show']);
    Route::put('/{id}', [IgvController::class, 'update']);
    Route::delete('/{id}', [IgvController::class, 'destroy']);
    Route::patch('/{id}/cambiar-estado', [IgvController::class, 'cambiarEstadoAutomatico']);
});
