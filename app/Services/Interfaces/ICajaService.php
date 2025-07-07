<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ICajaService extends IBaseService
{
    public function obtenerPorSucursal(int $sucursalId): Collection;
    public function obtenerAbiertaPorSucursal(int $sucursalId): ?Model;
    public function abrirCaja(array $datos): Model;
    public function cerrarCaja(int $cajaId, array $datos): Model;
    public function calcularMontoFinalEsperado(int $cajaId): float;
    public function obtenerMovimientosRecientes(int $cajaId, int $limite = 10): Collection;
} 