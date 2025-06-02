<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\GrupoRestauranteController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MesaController;
use App\Http\Controllers\Web\OrdenController;
use App\Http\Controllers\Web\PerfilController;
use App\Http\Controllers\Web\PlanSuscripcionController;
use App\Http\Controllers\Web\RestauranteController;
use App\Http\Controllers\Web\SucursalController;
use App\Http\Controllers\Web\TenantController;
use App\Http\Controllers\Web\UsuarioController;
use App\Http\Controllers\Web\MetodoPagoController;
use App\Http\Controllers\Web\IgvController;
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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('mesero')->group(function () {
// Rutas protegidas por autenticación
    Route::middleware([WebAuthenticate::class, WebCheckRole::class . ':mesero'])->group(function () {
        Route::get('/', static function () {
            return view('mesero.dashboard');
        })->name('mesero.dashboard');

        Route::prefix('ordenes')->group(function () {
            Route::get('/', [OrdenController::class, 'index'])->name('mesero.orden.index');
            Route::get('/crear-orden', [OrdenController::class, 'create'])->name('mesero.orden.store');
            Route::post('/crear-orden', [OrdenController::class, 'store'])->name('mesero.orden.store.submit');
            Route::get('/{orden}', [OrdenController::class, 'show'])->name('mesero.orden.show');
            Route::post('/ordenar', [OrdenController::class, 'ordenar'])->name('orden.ordenar');
            Route::post('/{id}/cambiar-estado', [OrdenController::class, 'cambiarEstado'])
                ->name('orden.cambiar-estado');
            Route::post('/{id}/marcar-servida', [OrdenController::class, 'marcarServida'])->name('orden.marcar-servida');
        });

        Route::get('/perfil', [PerfilController::class, 'index'])->name('mesero.perfil');
    });


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

        // Rutas de perfil
        Route::get('/perfil', [PerfilController::class, 'index'])->name('tenant.perfil');
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

        // Rutas de perfil
        Route::get('/perfil', [PerfilController::class, 'index'])->name('gerente.perfil');
        Route::post('/perfil', [PerfilController::class, 'actualizar'])->name('gerente.perfil.actualizar');
        Route::post('/perfil/contrasenia', [PerfilController::class, 'actualizarContrasenia'])->name('gerente.perfil.contrasenia');
    });
});

// Rutas de super administrador
Route::prefix('superadmin')->group(function () {
    Route::middleware([WebAuthenticate::class, WebCheckRole::class . ':superadmin'])->group(function () {
        Route::get('/dashboard', static function () {
            return view('super-admin.dashboard');
        })->name('superadmin.dashboard');

        // Ruta de perfil
        Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');

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
            Route::get('/{plan}', [PlanSuscripcionController::class, 'show'])
                ->name('superadmin.planes.show');
            Route::post('/{plan}', [PlanSuscripcionController::class, 'update'])->name('planes.update');
            Route::post('/{plan}/toggle-activo', [PlanSuscripcionController::class, 'toggleActivo'])->name('superadmin.planes.toggle-activo');
        });
        Route::prefix('pago')->group(function () {
            Route::get('/', [MetodoPagoController::class, 'index'])->name('superadmin.pago');
            Route::post('/', [MetodoPagoController::class, 'store'])->name('superadmin.pago.store');
            Route::put('/{id}', [MetodoPagoController::class, 'update'])->name('superadmin.pago.update');
            Route::post('/{id}/toggle-activo', [MetodoPagoController::class, 'toggleActivo'])->name('superadmin.pago.toggle-activo');
        });
        Route::prefix('igv')->group(function () {
            Route::get('/', [IgvController::class, 'index'])->name('superadmin.igv');
            Route::post('/', [IgvController::class, 'store'])->name('superadmin.igv.store');
            Route::put('/{id}', [IgvController::class, 'update'])->name('superadmin.igv.update');
            Route::post('/{id}/toggle-activo', [IgvController::class, 'toggleActivo'])->name('superadmin.igv.toggle-activo');
        });
    });
});

