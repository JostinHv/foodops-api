<?php

use App\Http\Controllers\Api\ItemMenuController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'items-menu'
], function () {
    Route::get('/', [ItemMenuController::class, 'index']);
    Route::post('/', [ItemMenuController::class, 'store']);
    Route::get('/{id}', [ItemMenuController::class, 'show']);
    Route::put('/{id}', [ItemMenuController::class, 'update']);
    Route::delete('/{id}', [ItemMenuController::class, 'destroy']);
});
