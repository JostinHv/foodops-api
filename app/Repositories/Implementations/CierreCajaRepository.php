<?php

namespace App\Repositories\Implementations;

use App\Models\CierreCaja;
use App\Repositories\Interfaces\ICierreCajaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CierreCajaRepository extends BaseRepository implements ICierreCajaRepository
{
    public function __construct(CierreCaja $modelo)
    {
        parent::__construct($modelo);
    }

    public function obtenerPorCaja(int $cajaId): Collection
    {
        return $this->modelo->where('caja_id', $cajaId)->get();
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