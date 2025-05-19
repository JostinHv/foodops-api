<?php

use App\Http\Controllers\Api\RolController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'roles'
], function () {
    Route::get('/', [RolController::class, 'index']);
    Route::post('/', [RolController::class, 'store']);
    Route::get('/{id}', [RolController::class, 'show']);
    Route::put('/{id}', [RolController::class, 'update']);
    Route::delete('/{id}', [RolController::class, 'destroy']);
});
