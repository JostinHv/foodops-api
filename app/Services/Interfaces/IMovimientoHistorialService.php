<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface IMovimientoHistorialService
{
    public function obtenerMovimientos(
        array $filtros = [],
        string $ordenarPor = 'created_at',
        string $orden = 'desc',
        int $porPagina = 10
    ): LengthAwarePaginator;
} 