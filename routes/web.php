<?php

use App\Http\Controllers\Api\OrdenController;

use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-submit');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register-submit');
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');

//Rutas del mesero
Route::get('/mesero', function () {
    return view('mesero.orden');
})->name('mesero');
Route::get('/mesero/ordenes/nueva', [OrdenController::class, 'create'])->name('orden-nueva');
Route::post('/mesero/ordenes', [OrdenController::class, 'store'])->name('ordenes');

Route::get('/mesero', function () {
    // Simula un usuario con el rol mesero (no se guarda en base de datos)
    $fakeUser = (object)[
        'name' => 'Juan Pérez',
        'role' => 'mesero',
        'foto' => null
    ];

    // Compartimos la variable con la vista
    return view('mesero.orden', ['user' => $fakeUser]);
});
