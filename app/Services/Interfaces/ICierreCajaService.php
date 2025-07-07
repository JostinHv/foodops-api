<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ICierreCajaService extends IBaseService
{
    public function obtenerPorCaja(int $cajaId): Collection;
    public function registrarCierre(array $datos): Model;
    public function calcularTotalesCierre(int $cajaId): array;
} 