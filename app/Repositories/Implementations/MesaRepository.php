<?php

namespace App\Repositories\Implementations;

use App\Models\Mesa;
use App\Repositories\Interfaces\IMesaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MesaRepository extends BaseRepository implements IMesaRepository
{
    public function __construct(Mesa $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['sucursal_id'])) {
            $consulta->where('sucursal_id', $filtros['sucursal_id']);
        }
        if (isset($filtros['estado_mesa_id'])) {
            $consulta->where('estado_mesa_id', $filtros['estado_mesa_id']);
        }
        if (isset($filtros['capacidad_min'])) {
            $consulta->where('capacidad', '>=', $filtros['capacidad_min']);
        }
        if (isset($filtros['capacidad_max'])) {
            $consulta->where('capacidad', '<=', $filtros['capacidad_max']);
        }
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            } else {
                $consulta->where('nombre', 'like', '%' . $searchTerm . '%');
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortField === 'capacidad' ? 'desc' : ($sortOrder ?? 'asc'));
        } else {
            $consulta->orderBy('nombre', 'asc');
        }
    }

    public function obtenerMesasDisponibles(): Collection
    {
        return $this->modelo
            ->where('estado_mesa_id', 1) // Estado 1 representa "Libre"
            ->get();
    }
}
