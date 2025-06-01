<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\GrupoRestauranteController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MesaController;
use App\Http\Controllers\Web\PlanSuscripcionController;
use App\Http\Controllers\Web\RestauranteController;
use App\Http\Controllers\Web\SucursalController;
use App\Http\Controllers\Web\TenantController;
use App\Http\Controllers\Web\UsuarioController;
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
        Route::get('/', static function () {
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
    Route::middleware([WebAuthenticate::class, WebCheckRole::class . ':administrador'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('tenant.dashboard');
        Route::prefix('grupos')->group(function () {
            Route::get('/', [GrupoRestauranteController::class, 'index'])
                ->name('tenant.grupo-restaurant');
            Route::get('/{grupo}', [GrupoRestauranteController::class, 'show'])
                ->name('tenant.grupo-restaurant.show');
            Route::post('/', [GrupoRestauranteController::class, 'store'])
                ->name('tenant.grupo-restaurant.store');
            Route::put('/{grupo}', [GrupoRestauranteController::class, 'update'])
                ->name('tenant.grupo-restaurant.update');
        });
        Route::prefix('restaurantes')->group(function () {
            Route::get('/', [RestauranteController::class, 'index'])
                ->name('tenant.restaurantes');
            Route::get('/{restaurante}', [RestauranteController::class, 'show'])
                ->name('tenant.restaurantes.show');
            Route::post('/', [RestauranteController::class, 'store'])
                ->name('tenant.restaurantes.store');
            Route::put('/{restaurante}', [RestauranteController::class, 'update'])
                ->name('tenant.restaurantes.update');
            Route::delete('/{restaurante}', [RestauranteController::class, 'destroy'])
                ->name('tenant.restaurantes.destroy');
            Route::put('/{restaurante}/toggle-activo', [RestauranteController::class, 'toggleActivo'])
                ->name('tenant.restaurantes.toggle-activo');
        });
        Route::prefix('sucursales')->group(function () {
            Route::get('/', [SucursalController::class, 'index'])
                ->name('tenant.sucursales');
            Route::get('/{sucursal}', [SucursalController::class, 'show'])
                ->name('tenant.sucursales.show');
            Route::post('/', [SucursalController::class, 'store'])
                ->name('tenant.sucursales.store');
            Route::post('/{sucursal}', [SucursalController::class, 'update'])
                ->name('tenant.sucursales.update');
            Route::post('/{sucursal}/toggle-activo', [SucursalController::class, 'toggleActivo'])
                ->name('tenant.sucursales.toggle-activo');
        });
        Route::prefix('usuarios')->group(function () {
            Route::get('/', [UsuarioController::class, 'index'])
                ->name('tenant.usuarios');
            Route::post('/', [UsuarioController::class, 'store'])
                ->name('tenant.usuarios.store');
            Route::get('/{usuario}', [UsuarioController::class, 'show'])
                ->name('tenant.usuarios.show');
            Route::post('/{usuario}', [UsuarioController::class, 'update'])
                ->name('tenant.usuarios.update');
            Route::post('/{usuario}/toggle-activo', [UsuarioController::class, 'toggleActivo'])
                ->name('tenant.usuarios.toggle-activo');
        });
    });
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
        Route::group(['prefix' => 'mesas'], function () {
            Route::get('/', [MesaController::class, 'index'])
                ->name('gerente.mesas');
            Route::post('/', [MesaController::class, 'store'])
                ->name('gerente.mesas.store');
            Route::get('/{id}', [MesaController::class, 'show'])
                ->name('gerente.mesas.show');
            Route::get('/{id}/edit', [MesaController::class, 'edit'])
                ->name('gerente.mesas.edit');
            Route::put('/{id}', [MesaController::class, 'update'])
                ->name('gerente.mesas.update');
            Route::delete('/{id}', [MesaController::class, 'destroy'])
                ->name('gerente.mesas.destroy');
            Route::post('/{id}/estado', [MesaController::class, 'cambiarEstado'])
                ->name('gerente.mesas.cambiar-estado');
        });
        Route::prefix('personal')->group(function () {
            Route::get('/', static function () {
                return view('gerente-sucursal.personal');
            })->name('gerente.personal');
        });
        Route::get('/facturacion', function () {
            return view('gerente-sucursal.facturacion');
        })->name('gerente.facturacion');
    });
});

// Rutas de super administrador
Route::prefix('superadmin')->group(function () {
    Route::middleware([WebAuthenticate::class, WebCheckRole::class . ':superadmin'])->group(function () {
        Route::get('/dashboard', static function () {
            return view('super-admin.dashboard');
        })->name('superadmin.dashboard');

        Route::prefix('tenant')->group(function () {
            Route::get('/', [TenantController::class, 'index'])->name('superadmin.tenant');
            Route::post('/', [TenantController::class, 'store'])->name('superadmin.tenant.store');
            Route::get('/{id}', [TenantController::class, 'show'])->name('superadmin.tenant.show');
            Route::put('/{id}', [TenantController::class, 'update'])->name('superadmin.tenant.update');
            Route::delete('/{id}', [TenantController::class, 'destroy'])->name('superadmin.tenant.destroy');
            Route::put('/{id}/toggle-activo', [TenantController::class, 'toggleActivo'])->name('superadmin.tenant.toggle-activo');
            // Rutas para gestión de usuarios del tenant
            Route::post('/{id}/usuarios', [TenantController::class, 'agregarUsuario'])
                ->name('superadmin.tenant.usuarios.store');
            Route::delete('/{tenantId}/usuarios/{usuarioId}', [TenantController::class, 'desactivarUsuario'])
                ->name('superadmin.tenant.usuarios.destroy');
            Route::post('/{tenantId}/usuarios/{usuarioId}/rol', [TenantController::class, 'cambiarRolUsuario'])
                ->name('superadmin.tenant.usuarios.cambiar-rol');
        });

        Route::prefix('planes')->group(function () {
            Route::get('/', [PlanSuscripcionController::class, 'index'])->name('planes');
            Route::post('/', [PlanSuscripcionController::class, 'store'])->name('superadmin.planes.store');
            Route::get('/{plan}', [PlanSuscripcionController::class, 'show'])->name('planes.show');
            Route::put('/{plan}', [PlanSuscripcionController::class, 'update'])->name('planes.update');
            Route::put('/{plan}/toggle-activo', [PlanSuscripcionController::class, 'toggleActivo'])->name('superadmin.planes.toggle-activo');
        });
        Route::prefix('pago')->group(function () {
            Route::get('/', static function () {
                return view('super-admin.pago');
            })->name('superadmin.pago');
        });
        Route::prefix('igv')->group(function () {
            Route::get('/', static function () {
                return view('super-admin.igv');
            })->name('superadmin.igv');
        });
    });
});

