<?php

use App\Http\Controllers\Api\OrdenController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'ordenes'
], function () {
    Route::get('/', [OrdenController::class, 'index']);
    Route::post('/', [OrdenController::class, 'store']);
    Route::get('/{id}', [OrdenController::class, 'show']);
    Route::put('/{id}', [OrdenController::class, 'update']);
    Route::delete('/{id}', [OrdenController::class, 'destroy']);
});
