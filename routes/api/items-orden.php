<?php

use App\Http\Controllers\Api\ItemOrdenController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'items-orden'
], function () {
    Route::get('/', [ItemOrdenController::class, 'index']);
    Route::post('/', [ItemOrdenController::class, 'store']);
    Route::get('/{id}', [ItemOrdenController::class, 'show']);
    Route::put('/{id}', [ItemOrdenController::class, 'update']);
    Route::delete('/{id}', [ItemOrdenController::class, 'destroy']);
});
