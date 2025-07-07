<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IMovimientoCajaService extends IBaseService
{
    public function obtenerPorCaja(int $cajaId): Collection;
    public function registrarMovimiento(array $datos): Model;
    public function obtenerMovimientosPorTipo(int $cajaId, int $tipoMovimientoId): Collection;
    public function calcularTotalPorTipo(int $cajaId, int $tipoMovimientoId): float;
    public function calcularTotalPorMetodoPago(int $cajaId, int $metodoPagoId): float;
} 