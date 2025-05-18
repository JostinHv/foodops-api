<?php

namespace App\Repositories\Implementations;

use App\Models\UsuarioRol;
use App\Repositories\Interfaces\IUsuarioRolRepository;
use Illuminate\Database\Eloquent\Builder;

class UsuarioRolRepository extends ActivoBoolRepository implements IUsuarioRolRepository
{

    public function __construct(UsuarioRol $modelo
    )
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
