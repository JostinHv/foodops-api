<?php

namespace App\Repositories\Implementations;

use App\Models\Restaurante;
use App\Repositories\Interfaces\IRestauranteRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RestauranteRepository extends ActivoBoolRepository implements IRestauranteRepository
{
    public function __construct(Restaurante $modelo)
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

    public function obtenerRestaurantesPorTenant(int $tenantId): Collection
    {
        return $this->modelo
            ->with(['grupoRestaurantes', 'logo'])
            ->where('tenant_id', $tenantId)
            ->get();
    }

    public function obtenerPorTenantId(int $tenantId): Collection
    {
        return $this->modelo->where('tenant_id', $tenantId)->get();
    }
}
