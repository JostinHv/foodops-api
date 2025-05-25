<?php

use App\Http\Controllers\Web\OrdenController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrdenController::class, 'index'])->name('orden-index');
Route::get('/crear-orden', [OrdenController::class, 'create'])->name('orden-store');
Route::post('/crear-orden', [OrdenController::class, 'store'])->name('orden-store-submit');
