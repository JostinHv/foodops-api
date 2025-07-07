<?php

namespace App\Repositories\Implementations;

use App\Models\Caja;
use App\Repositories\Interfaces\ICajaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CajaRepository extends BaseRepository implements ICajaRepository
{
    public function __construct(Caja $modelo)
    {
        parent::__construct($modelo);
    }

    public function obtenerPorSucursal(int $sucursalId): Collection
    {
        return $this->modelo->where('sucursal_id', $sucursalId)->get();
    }

    public function obtenerAbiertaPorSucursal(int $sucursalId): ?Model
    {
        return $this->modelo->where('sucursal_id', $sucursalId)
            ->whereHas('estadoCaja', function ($q) {
                $q->where('nombre', 'ABIERTA');
            })
            ->with(['usuario', 'estadoCaja'])
            ->first();
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