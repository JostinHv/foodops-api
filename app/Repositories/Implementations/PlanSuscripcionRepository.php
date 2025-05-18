<?php

namespace App\Repositories\Implementations;

use App\Models\PlanSuscripcion;
use App\Repositories\Interfaces\IPlanSuscripcionRepository;
use Illuminate\Database\Eloquent\Builder;

class PlanSuscripcionRepository extends ActivoBoolRepository implements IPlanSuscripcionRepository
{
    public function __construct(PlanSuscripcion $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['nombre'])) {
            $consulta->where('nombre', 'like', '%' . $filtros['nombre'] . '%');
        }
        if (isset($filtros['precio'])) {
            $consulta->where('precio', $filtros['precio']);
        }
        if (isset($filtros['intervalo'])) {
            $consulta->where('intervalo', $filtros['intervalo']);
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
                        ->orWhere('descripcion', 'like', '%' . $searchTerm . '%')
                        ->orWhere('intervalo', 'like', '%' . $searchTerm . '%');
                });
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortOrder ?? 'asc');
        } else {
            $consulta->orderBy('precio', 'asc');
        }
    }
}
