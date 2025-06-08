<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IMovimientoHistorialRepository;
use App\Services\Interfaces\IMovimientoHistorialService;
use Illuminate\Pagination\LengthAwarePaginator;

class MovimientoHistorialService implements IMovimientoHistorialService
{
    private IMovimientoHistorialRepository $movimientoHistorialRepository;

    public function __construct(IMovimientoHistorialRepository $movimientoHistorialRepository)
    {
        $this->movimientoHistorialRepository = $movimientoHistorialRepository;
    }

    public function obtenerMovimientos(
        array $filtros = [],
        string $ordenarPor = 'created_at',
        string $orden = 'desc',
        int $porPagina = 10
    ): LengthAwarePaginator {
        return $this->movimientoHistorialRepository->obtenerMovimientos(
            $filtros,
            $ordenarPor,
            $orden,
            $porPagina
        );
    }
} 