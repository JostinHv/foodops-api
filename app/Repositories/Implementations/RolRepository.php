<?php

namespace App\Repositories\Implementations;

use App\Models\Rol;
use App\Repositories\Interfaces\IRolRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RolRepository extends ActivoBoolRepository implements IRolRepository
{
    public function __construct(Rol $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['nombre'])) {
            $consulta->where('nombre', 'like', '%' . $filtros['nombre'] . '%');
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

    public function obtenerRolesActivosPorId(array $ids): Collection
    {
        return $this->modelo
            ->whereIn('id', $ids)
            ->where('activo', true)
            ->get();
    }
}
