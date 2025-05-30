<?php

use App\Http\Controllers\Api\OrdenController;
use App\Http\Middleware\ApiAuthenticate;
use App\Http\Middleware\ApiCheckRole;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'ordenes'
], function () {
    Route::middleware([ApiAuthenticate::class, ApiCheckRole::class . ':mesero'])->group(function () {
        Route::get('/', [OrdenController::class, 'index']);
        Route::post('/', [OrdenController::class, 'store']);
        Route::get('/{id}', [OrdenController::class, 'show']);
        Route::put('/{id}', [OrdenController::class, 'update']);
        Route::delete('/{id}', [OrdenController::class, 'destroy']);
        Route::post('/crear-orden', [OrdenController::class, 'crearOrden']);
        Route::get('/obtener/sucursal', [OrdenController::class, 'obtenerOrdenesPorSucursal']);
    });
});
