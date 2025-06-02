<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    require __DIR__ . '/api/auth.php';
    require __DIR__ . '/api/asignaciones-personal.php';
    require __DIR__ . '/api/categorias.php';
    require __DIR__ . '/api/estados-mesa.php';
    require __DIR__ . '/api/estados-orden.php';
    require __DIR__ . '/api/facturas.php';
    require __DIR__ . '/api/grupo-restaurantes.php';
    require __DIR__ . '/api/historial-pagos.php';
    require __DIR__ . '/api/igv.php';
    require __DIR__ . '/api/imagenes.php';
    require __DIR__ . '/api/items-menu.php';
    require __DIR__ . '/api/items-orden.php';
    require __DIR__ . '/api/limites-uso.php';
    require __DIR__ . '/api/mesas.php';
    require __DIR__ . '/api/metodos-pago.php';
    require __DIR__ . '/api/ordenes.php';
    require __DIR__ . '/api/planes-suscripcion.php';
    require __DIR__ . '/api/reservas.php';
    require __DIR__ . '/api/restaurantes.php';
    require __DIR__ . '/api/roles.php';
    require __DIR__ . '/api/sucursales.php';
    require __DIR__ . '/api/tenants.php';
    require __DIR__ . '/api/tenant-suscripciones.php';
    require __DIR__ . '/api/usuarios.php';
    require __DIR__ . '/api/comandos.php';
});
