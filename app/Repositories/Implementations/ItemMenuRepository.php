<?php

namespace App\Repositories\Implementations;

use App\Models\ItemMenu;
use App\Repositories\Interfaces\IItemMenuRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItemMenuRepository extends BaseRepository implements IItemMenuRepository
{
    public function __construct(ItemMenu $modelo)
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

    public function obtenerTodosItemsDisponibles(): Collection
    {
        return $this->modelo
            ->where('disponible', true)
            ->where('activo', true)
            ->orderBy('orden_visualizacion', 'desc')
            ->get();
    }
}
