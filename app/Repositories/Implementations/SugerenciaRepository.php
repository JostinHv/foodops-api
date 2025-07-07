<?php

namespace App\Repositories\Implementations;

use App\Models\Sugerencia;
use App\Repositories\Interfaces\ISugerenciaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SugerenciaRepository extends BaseRepository implements ISugerenciaRepository
{
    public function __construct(Sugerencia $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        // Implementar filtros personalizados si es necesario
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        // Implementar búsqueda personalizada si es necesario
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        // Implementar ordenamiento personalizado si es necesario
    }

    public function obtenerPorUsuarioId(int $usuarioId): Collection
    {
        return $this->modelo->where('usuario_id', $usuarioId)->orderByDesc('created_at')->get();
    }
} 