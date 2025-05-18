<?php

namespace App\Repositories\Implementations;

use App\Models\AsignacionPersonal;
use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use Illuminate\Database\Eloquent\Builder;

class AsignacionPersonalRepository extends ActivoBoolRepository implements IAsignacionPersonalRepository
{

    public function __construct(AsignacionPersonal $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm && $searchColumn) {
            $consulta->where($searchColumn, 'LIKE', "%$searchTerm%");
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField && $sortOrder) {
            $consulta->orderBy($sortField, $sortOrder);
        }
    }
}
