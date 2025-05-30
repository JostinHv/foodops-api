<?php

use App\Http\Controllers\Api\MesaController;
use App\Http\Middleware\ApiAuthenticate;
use App\Http\Middleware\ApiCheckRole;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'mesas'
], static function () {
    Route::middleware([ApiAuthenticate::class, ApiCheckRole::class . ':mesero'])->group(function () {
        Route::get('/', [MesaController::class, 'index']);
        Route::post('/', [MesaController::class, 'store']);
        Route::get('/{id}', [MesaController::class, 'show']);
        Route::put('/{id}', [MesaController::class, 'update']);
        Route::delete('/{id}', [MesaController::class, 'destroy']);
        Route::get('/obtener/sucursal', [MesaController::class, 'obtenerMesasPorSucursal']);
        Route::put('/estado/cambiar/{id}', [MesaController::class, 'cambiarEstadoMesa']);
    });
});
