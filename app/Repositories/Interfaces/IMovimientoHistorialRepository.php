<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface IMovimientoHistorialRepository extends IBaseRepository
{
    public function obtenerMovimientos(
        array $filtros = [],
        string $ordenarPor = 'created_at',
        string $orden = 'desc',
        int $porPagina = 10
    ): LengthAwarePaginator;
} 