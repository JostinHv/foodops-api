<?php

namespace App\Repositories\Implementations;

use App\Models\EstadoMesa;
use App\Repositories\Interfaces\IEstadoMesaRepository;
use Illuminate\Database\Eloquent\Builder;

class EstadoMesaRepository extends ActivoBoolRepository implements IEstadoMesaRepository
{
    public function __construct(EstadoMesa $modelo)
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
