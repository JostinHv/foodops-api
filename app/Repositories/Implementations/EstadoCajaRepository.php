<?php

namespace App\Repositories\Implementations;

use App\Models\EstadoCaja;
use App\Repositories\Interfaces\IEstadoCajaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EstadoCajaRepository extends BaseRepository implements IEstadoCajaRepository
{
    public function __construct(EstadoCaja $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        // Implementar filtros personalizados si es necesario
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm && $searchColumn) {
            $consulta->where($searchColumn, 'like', "%$searchTerm%");
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField && $sortOrder) {
            $consulta->orderBy($sortField, $sortOrder);
        }
    }
} 