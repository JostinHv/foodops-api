<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MesaController;
use App\Http\Middleware\WebAuthenticate;
use App\Http\Middleware\WebCheckRole;
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
    Route::middleware([WebAuthenticate::class, WebCheckRole::class . ':mesero'])->group(function () {
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

Route::prefix('tenant')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('tenant.dashboard');

    Route::get('/grupos', [AdminController::class, 'grupos'])
        ->name('tenant.grupo-restaurant');
    Route::post('/grupos', [AdminController::class, 'store'])->name('grupos.store');
    Route::get('/restaurants', [AdminController::class, 'restaurants'])
        ->name('tenant.restaurantes');
    Route::get('/sucursales', [AdminController::class, 'sucursales'])
        ->name('tenant.sucursales');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])
        ->name('tenant.usuarios');
});

//Rutas de gerente de sucursal
Route::prefix('gerente')->group(function () {
    Route::middleware([WebAuthenticate::class, WebCheckRole::class . ':gerente'])->group(function () {
        Route::get('/dashboard', static function () {
            return view('gerente-sucursal.dashboard');
        })->name('gerente.dashboard');
        Route::get('/menu', static function () {
            return view('gerente-sucursal.menu');
        })->name('gerente.menu');
        Route::get('/mesas', [MesaController::class, 'index'])
            ->name('gerente.mesas');
        Route::post('/mesas', [MesaController::class, 'store'])
            ->name('gerente.mesas.store');
        Route::get('/mesas/{id}', [MesaController::class, 'show'])
            ->name('gerente.mesas.show');
        Route::get('/mesas/{id}/edit', [MesaController::class, 'edit'])
            ->name('gerente.mesas.edit');
        Route::put('/mesas/{id}', [MesaController::class, 'update'])
            ->name('gerente.mesas.update');
        Route::delete('/mesas/{id}', [MesaController::class, 'destroy'])
            ->name('gerente.mesas.destroy');
        Route::post('/mesas/{id}/estado', [MesaController::class, 'cambiarEstado'])
            ->name('gerente.mesas.cambiar-estado');
        Route::get('/personal', static function () {
            return view('gerente-sucursal.personal');
        })->name('gerente.personal');
        Route::get('/facturacion', function () {
            return view('gerente-sucursal.facturacion');
        })->name('gerente.facturacion');
    });
});

// Rutas de super administrador
Route::prefix('superadmin')->group(function () {
    Route::get('/dashboard', function () {
        return view('super-admin.dashboard');
    })->name('superadmin.dashboard');
    Route::get('/tenant', function () {
        return view('super-admin.tenant');
    })->name('superadmin.tenant');
});
