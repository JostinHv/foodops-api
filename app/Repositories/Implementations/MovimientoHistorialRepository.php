<?php

namespace App\Repositories\Implementations;

use App\Models\MovimientoHistorial;
use App\Repositories\Interfaces\IMovimientoHistorialRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class MovimientoHistorialRepository extends BaseRepository implements IMovimientoHistorialRepository
{
    public function __construct(MovimientoHistorial $modelo)
    {
        parent::__construct($modelo);
    }

    public function obtenerMovimientos(
        array $filtros = [],
        string $ordenarPor = 'created_at',
        string $orden = 'desc',
        int $porPagina = 10
    ): LengthAwarePaginator {
        $query = $this->modelo->with('usuario');

        // Aplicar filtros
        if (!empty($filtros['usuario_id'])) {
            $query->where('usuario_id', $filtros['usuario_id']);
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['tabla_modificada'])) {
            $query->where('tabla_modificada', 'like', '%' . $filtros['tabla_modificada'] . '%');
        }

        // Aplicar filtro de fecha o intervalo
        if (!empty($filtros['intervalo'])) {
            $now = Carbon::now();
            switch ($filtros['intervalo']) {
                case 'hoy':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'ayer':
                    $query->whereDate('created_at', $now->subDay()->toDateString());
                    break;
                case 'ultima_semana':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'ultimo_mes':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
            }
        } elseif (!empty($filtros['fecha_inicio'])) {
             $query->where('created_at', '>=', Carbon::parse($filtros['fecha_inicio']));
        }

        // Ordenamiento
        $query->orderBy($ordenarPor, $orden);

        return $query->paginate($porPagina);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['usuario_id'])) {
            $consulta->where('usuario_id', $filtros['usuario_id']);
        }
        if (isset($filtros['tipo'])) {
            $consulta->where('tipo', $filtros['tipo']);
        }
        if (isset($filtros['tabla_modificada'])) {
            $consulta->where('tabla_modificada', 'like', '%' . $filtros['tabla_modificada'] . '%');
        }
        
        // Aplicar filtro de fecha o intervalo
        if (!empty($filtros['intervalo'])) {
            $now = Carbon::now();
            switch ($filtros['intervalo']) {
                case 'hoy':
                    $consulta->whereDate('created_at', $now->toDateString());
                    break;
                case 'ayer':
                    $consulta->whereDate('created_at', $now->subDay()->toDateString());
                    break;
                case 'ultima_semana':
                    $consulta->where('created_at', '>=', $now->subWeek());
                    break;
                case 'ultimo_mes':
                    $consulta->where('created_at', '>=', $now->subMonth());
                    break;
            }
        } elseif (isset($filtros['fecha_inicio'])) {
             $consulta->where('created_at', '>=', Carbon::parse($filtros['fecha_inicio']));
        }
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            } else {
                $consulta->where('tabla_modificada', 'like', '%' . $searchTerm . '%');
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortOrder ?? 'asc');
        } else {
            $consulta->orderBy('created_at', 'desc');
        }
    }
}
