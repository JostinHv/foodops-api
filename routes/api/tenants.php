<?php

use App\Http\Controllers\Api\TenantController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'tenants'
], function () {
    Route::get('/', [TenantController::class, 'index']);
    Route::post('/', [TenantController::class, 'store']);
    Route::get('/{id}', [TenantController::class, 'show']);
    Route::put('/{id}', [TenantController::class, 'update']);
    Route::delete('/{id}', [TenantController::class, 'destroy']);
});
