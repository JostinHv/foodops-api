<?php

use App\Http\Controllers\Api\PlanSuscripcionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'planes-suscripcion'
], function () {
    Route::get('/', [PlanSuscripcionController::class, 'index']);
    Route::post('/', [PlanSuscripcionController::class, 'store']);
    Route::get('/{id}', [PlanSuscripcionController::class, 'show']);
    Route::put('/{id}', [PlanSuscripcionController::class, 'update']);
    Route::delete('/{id}', [PlanSuscripcionController::class, 'destroy']);
    Route::patch('/{id}/cambiar-estado', [PlanSuscripcionController::class, 'cambiarEstadoAutomatico']);
});
