<?php

namespace App\Repositories\Implementations;

use App\Models\Igv;
use App\Repositories\Interfaces\IIgvRepository;
use Illuminate\Database\Eloquent\Builder;

class IgvRepository extends ActivoBoolRepository implements IIgvRepository
{
    public function __construct(Igv $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['anio'])) {
            $consulta->where('anio', $filtros['anio']);
        }
        if (isset($filtros['valor_decimal'])) {
            $consulta->where('valor_decimal', $filtros['valor_decimal']);
        }
        if (isset($filtros['valor_porcentaje'])) {
            $consulta->where('valor_porcentaje', $filtros['valor_porcentaje']);
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
                $consulta->where('anio', 'like', '%' . $searchTerm . '%');
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortOrder ?? 'asc');
        } else {
            $consulta->orderBy('anio', 'desc');
        }
    }
}
