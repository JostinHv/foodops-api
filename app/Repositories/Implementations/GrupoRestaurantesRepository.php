<?php

namespace App\Repositories\Implementations;

use App\Models\GrupoRestaurantes;
use App\Repositories\Interfaces\IGrupoRestaurantesRepository;
use Illuminate\Database\Eloquent\Builder;

class GrupoRestaurantesRepository extends BaseRepository implements IGrupoRestaurantesRepository
{
    public function __construct(GrupoRestaurantes $modelo)
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
