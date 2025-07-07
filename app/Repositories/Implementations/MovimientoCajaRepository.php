<?php

namespace App\Repositories\Implementations;

use App\Models\MovimientoCaja;
use App\Repositories\Interfaces\IMovimientoCajaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MovimientoCajaRepository extends BaseRepository implements IMovimientoCajaRepository
{
    public function __construct(MovimientoCaja $modelo)
    {
        parent::__construct($modelo);
    }

    public function obtenerPorCaja(int $cajaId): Collection
    {
        return $this->modelo->where('caja_id', $cajaId)
            ->with(['tipoMovimientoCaja', 'metodoPago', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();
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