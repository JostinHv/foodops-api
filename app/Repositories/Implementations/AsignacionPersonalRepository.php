<?php

namespace App\Repositories\Implementations;

use App\Models\AsignacionPersonal;
use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AsignacionPersonalRepository extends ActivoBoolRepository implements IAsignacionPersonalRepository
{

    public function __construct(AsignacionPersonal $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm && $searchColumn) {
            $consulta->where($searchColumn, 'LIKE', "%$searchTerm%");
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField && $sortOrder) {
            $consulta->orderBy($sortField, $sortOrder);
        }
    }

    public function buscarPorUsuarioId(mixed $usuarioId): ?Model
    {
        return $this->modelo->where('usuario_id', $usuarioId)->where('activo', true)->first();
    }

    public function obtenerPorUsuarioId(int $usuarioId): ?Model
    {
        return $this->modelo->where('usuario_id', $usuarioId)->where('activo', true)->first();
    }

    public function obtenerPorSucursalId(int $sucursalId): Collection
    {
        return $this->modelo->where('sucursal_id', $sucursalId)->where('activo', true)->get();
    }

    public function obtenerPorTenantId(int $tenantId): Collection
    {
        return $this->modelo->where('tenant_id', $tenantId)->where('activo', true)->get();
    }
}
