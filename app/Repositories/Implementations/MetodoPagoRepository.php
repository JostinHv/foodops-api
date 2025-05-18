<?php

namespace App\Repositories\Implementations;

use App\Models\MetodoPago;
use App\Repositories\Interfaces\IMetodoPagoRepository;
use Illuminate\Database\Eloquent\Builder;

class MetodoPagoRepository extends ActivoBoolRepository implements IMetodoPagoRepository
{
    public function __construct(MetodoPago $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['nombre'])) {
            $consulta->where('nombre', 'like', '%' . $filtros['nombre'] . '%');
        }
        if (isset($filtros['descripcion'])) {
            $consulta->where('descripcion', 'like', '%' . $filtros['descripcion'] . '%');
        }
        if (isset($filtros['activo'])) {
            $consulta->where('activo', $filtros['activo']);
        }
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            } else {
                $consulta->where(function ($query) use ($searchTerm) {
                    $query->where('nombre', 'like', '%' . $searchTerm . '%')
                        ->orWhere('descripcion', 'like', '%' . $searchTerm . '%');
                });
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortOrder ?? 'asc');
        } else {
            $consulta->orderBy('nombre', 'asc');
        }
    }
}
