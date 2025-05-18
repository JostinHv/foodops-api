<?php

namespace App\Repositories\Implementations;

use App\Models\HistorialPagoSuscripcion;
use App\Repositories\Interfaces\IHistorialPagoSuscripcionRepository;
use Illuminate\Database\Eloquent\Builder;

class HistorialPagoSuscripcionRepository extends BaseRepository implements IHistorialPagoSuscripcionRepository
{
    public function __construct(HistorialPagoSuscripcion $modelo)
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
