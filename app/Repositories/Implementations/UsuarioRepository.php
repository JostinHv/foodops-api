<?php

namespace App\Repositories\Implementations;

use App\Models\Orden;
use App\Models\Usuario;
use App\Repositories\Interfaces\IOrdenRepository;
use App\Repositories\Interfaces\IUsuarioRepository;
use Illuminate\Database\Eloquent\Builder;

class UsuarioRepository extends ActivoBoolRepository implements IUsuarioRepository
{
    public function __construct(Usuario $modelo)
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
