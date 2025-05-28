<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Middleware\WebAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');


// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register-submit');
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('mesero')->group(function () {
// Rutas protegidas por autenticación
    Route::middleware([WebAuthenticate::class])->group(function () {
        Route::get('/', function () {
            return view('mesero.dashboard');
        })->name('mesero.dashboard');

        Route::prefix('ordenes')->group(function () {
            require __DIR__ . '/web/orden.php';
        });
    });

    // Rutas del cocinero
//    Route::prefix('cocinero')->middleware(['role:cocinero'])->group(function () {
//        Route::get('/', function () {
//            return view('cocinero.dashboard', ['user' => auth()->user()]);
//        })->name('cocinero.dashboard');
//
//        // Incluir rutas de órdenes bajo el prefijo 'ordenes'
//        Route::prefix('ordenes')->group(function () {
//            require __DIR__.'/web/orden.php';
//        });
//    });

    // Otras rutas protegidas aquí...

});

Route::prefix('tenant')->group(function(){
    Route::get('/dashboard', [AdminController::class,'dashboard'])
         ->name('tenant.dashboard');

    Route::get('/grupos', [AdminController::class, 'grupos'])
        ->name('tenant.grupo-restaurant');
    Route::post('/grupos', [AdminController::class, 'store'])->name('grupos.store');

});


