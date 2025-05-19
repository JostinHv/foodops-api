<?php

use App\Http\Controllers\Api\FacturaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'facturas'
], function () {
    Route::get('/', [FacturaController::class, 'index']);
    Route::post('/', [FacturaController::class, 'store']);
    Route::get('/{id}', [FacturaController::class, 'show']);
    Route::put('/{id}', [FacturaController::class, 'update']);
    Route::delete('/{id}', [FacturaController::class, 'destroy']);
});
