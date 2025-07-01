<?php

namespace App\Repositories\Implementations;

use App\Models\Orden;
use App\Repositories\Interfaces\IOrdenRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrdenRepository extends BaseRepository implements IOrdenRepository
{
    public function __construct(Orden $modelo)
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

    public function obtenerUltimoNumeroOrden(int $sucursalId): int
    {
        // Obtener el último registro ordenado por id descendente
        $ultimaOrden = $this->modelo
            ->where('sucursal_id', $sucursalId)
            ->orderBy('id', 'desc')
            ->first();
        return (int)$ultimaOrden->nro_orden ?: 0; // Retorna 0 si no hay órdenes
    }

    public function obtenerOrdenesPorSucursal(mixed $sucursal_id): Collection
    {
        return $this->modelo->where('sucursal_id', $sucursal_id)
            ->with('estadoOrden', 'sucursal', 'itemsOrdenes')
            ->where('estado_orden_id', '!=', 4)
            ->where('estado_orden_id', '!=', 8)
            ->orderBy('nro_orden', 'asc')
            ->get();
    }

    public function obtenerOrdenesPendientesPorSucursales(array $sucursalIds): Collection
    {
        return $this->modelo->with(['mesa', 'mesero', 'itemsOrdenes.itemMenu'])
            ->whereIn('sucursal_id', $sucursalIds)
            ->whereIn('estado_orden_id', [1, 2, 3, 4, 5, 7]) // 3 = Pendiente
            ->get();
    }

    public function obtenerItemsOrden(int $ordenId): Collection
    {
        return $this->modelo->find($ordenId)
            ->itemsOrdenes()
            ->with('itemMenu')
            ->get();
    }

    public function obtenerPorSucursal(int $sucursalId): Collection
    {
        return $this->modelo->where('sucursal_id', $sucursalId)
            ->with(['estadoOrden', 'sucursal', 'itemsOrdenes'])
            ->orderBy('nro_orden', 'asc')
            ->get();
    }

    public function obtenerPorMesero(int $meseroId): Collection
    {
        return $this->modelo->where('mesero_id', $meseroId)
            ->with(['estadoOrden', 'sucursal', 'itemsOrdenes'])
            ->orderBy('nro_orden', 'asc')
            ->get();
    }

    public function obtenerPorEstado(int $estadoId): Collection
    {
        return $this->modelo->where('estado_orden_id', $estadoId)
            ->with(['sucursal', 'mesero', 'itemsOrdenes'])
            ->orderBy('nro_orden', 'asc')
            ->get();
    }
}
