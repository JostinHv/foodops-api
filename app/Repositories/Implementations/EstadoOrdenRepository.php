<?php

namespace App\Repositories\Implementations;

use App\Models\EstadoOrden;
use App\Repositories\Interfaces\IEstadoOrdenRepository;
use Illuminate\Database\Eloquent\Builder;

class EstadoOrdenRepository extends ActivoBoolRepository implements IEstadoOrdenRepository
{
    public function __construct(EstadoOrden $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {

    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField && $sortOrder) {
            $consulta->orderBy($sortField, $sortOrder);
        }
    }
}
