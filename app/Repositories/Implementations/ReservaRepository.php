<?php

namespace App\Repositories\Implementations;

use App\Models\Reserva;
use App\Repositories\Interfaces\IReservaRepository;
use Illuminate\Database\Eloquent\Builder;

class ReservaRepository extends BaseRepository implements IReservaRepository
{
    public function __construct(Reserva $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['mesa_id'])) {
            $consulta->where('mesa_id', $filtros['mesa_id']);
        }
        if (isset($filtros['recepcionista_id'])) {
            $consulta->where('recepcionista_id', $filtros['recepcionista_id']);
        }
        if (isset($filtros['estado'])) {
            $consulta->where('estado', $filtros['estado']);
        }
        if (isset($filtros['fecha_desde'])) {
            $consulta->where('fecha_reserva', '>=', $filtros['fecha_desde']);
        }
        if (isset($filtros['fecha_hasta'])) {
            $consulta->where('fecha_reserva', '<=', $filtros['fecha_hasta']);
        }
        if (isset($filtros['tamanio_grupo_min'])) {
            $consulta->where('tamanio_grupo', '>=', $filtros['tamanio_grupo_min']);
        }
        if (isset($filtros['tamanio_grupo_max'])) {
            $consulta->where('tamanio_grupo', '<=', $filtros['tamanio_grupo_max']);
        }
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            } else {
                $consulta->where(function ($query) use ($searchTerm) {
                    $query->where('nombre_cliente', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email_cliente', 'like', '%' . $searchTerm . '%')
                        ->orWhere('telefono_cliente', 'like', '%' . $searchTerm . '%')
                        ->orWhere('notas', 'like', '%' . $searchTerm . '%');
                });
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortOrder ?? 'asc');
        } else {
            $consulta->orderBy('fecha_reserva', 'desc')
                ->orderBy('hora_inicio', 'asc');
        }
    }
}
