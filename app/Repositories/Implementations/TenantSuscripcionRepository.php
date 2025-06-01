<?php

namespace App\Repositories\Implementations;

use App\Models\TenantSuscripcion;
use App\Repositories\Interfaces\ITenantSuscripcionRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TenantSuscripcionRepository extends BaseRepository implements ITenantSuscripcionRepository
{
    public function __construct(TenantSuscripcion $modelo)
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

    public function obtenerTenantsPorPlan($id): Collection
    {
        return $this->modelo
            ->where('plan_suscripcion_id', $id)
            ->with(['tenant'])
            ->get();
    }
}
