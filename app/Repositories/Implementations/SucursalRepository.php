<?php

namespace App\Repositories\Implementations;

use App\Models\Sucursal;
use App\Repositories\Interfaces\ISucursalRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SucursalRepository extends ActivoBoolRepository implements ISucursalRepository
{
    public function __construct(Sucursal $modelo)
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

    public function obtenerUltimoActivo(): Collection
    {
        return $this->modelo->where('activo', true)->latest()->get();
    }

    public function obtenerPorUsuarioId(int $usuarioId): Collection
    {
        return $this->modelo->where('usuario_id', $usuarioId)->get();
    }
}
