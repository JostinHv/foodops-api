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

    public function obtenerUltimoNumeroOrden(): int
    {
        $ultimoNumero = $this->modelo->max('nro_orden');
        return $ultimoNumero ?: 0; // Retorna 0 si no hay órdenes
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

}
