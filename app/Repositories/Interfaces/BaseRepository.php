<?php

namespace App\Repositories\Interfaces;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $modelo;

    public function __construct(Model $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Obtiene todos los registros del modelo.
     *
     * @return Collection
     */
    public function obtenerTodos(): Collection
    {
        return $this->modelo->all();
    }

    /**
     * Obtiene un registro del modelo por su ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function obtenerPorId(int $id): ?Model
    {
        return $this->modelo->find($id);
    }

    /**
     * Crea un nuevo registro en el modelo.
     *
     * @param array $datos
     * @return Model
     */
    public function crear(array $datos): Model
    {
        DB::beginTransaction();
        try {
            $modelo = $this->modelo->create($datos);
            DB::commit();
            return $modelo;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al crear el registro: ' . $e->getMessage());
            return $this->modelo;
        }
    }

    /**
     * Actualiza un registro del modelo por su ID.
     *
     * @param int $id
     * @param array $datos
     * @return bool
     */
    public function actualizar(int $id, array $datos): bool
    {
        DB::beginTransaction();
        try {
            $entidad = $this->obtenerPorId($id);
            if ($entidad) {
                $resultado = $entidad->update($datos);
                DB::commit();
                return $resultado;
            }
            DB::rollBack();
            return false;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar el registro: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Elimina un registro del modelo por su ID.
     *
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool
    {
        DB::beginTransaction();
        try {
            $entidad = $this->obtenerPorId($id);
            if ($entidad) {
                $resultado = $entidad->delete();
                DB::commit();
                return $resultado;
            }
            DB::rollBack();
            return false;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar el registro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Inserta un nuevo registro en el modelo y obtiene su ID.
     *
     * @param array $datos
     * @return int
     */
    public function insertarObtenerId(array $datos): int
    {
        DB::beginTransaction();
        try {
            $id = $this->modelo->insertGetId($datos);
            DB::commit();
            return $id;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al insertar el registro: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el último ID insertado en el modelo.
     *
     * @return int
     */
    public function obtenerUltimoId(): int
    {
        return $this->modelo->select('id')->orderBy('id', 'desc')->first()->id ?? 0;
    }

    /**
     * Aplica los filtros a la consulta.
     *
     * @param Builder $consulta
     * @param array $filtros
     */
    abstract protected function aplicarFiltros(Builder $consulta, array $filtros): void;

    /**
     * Aplica la búsqueda a la consulta.
     *
     * @param Builder $consulta
     * @param string|null $searchTerm
     * @param string|null $searchColumn
     */
    abstract protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void;

    /**
     * Aplica el ordenamiento a la consulta.
     *
     * @param Builder $consulta
     * @param string|null $sortField
     * @param string|null $sortOrder
     */
    abstract protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void;

    /**
     * Obtiene los registros del modelo paginados.
     *
     * @param array $criterios
     * @param Builder|null $query
     * @return LengthAwarePaginator
     */
    public function obtenerPaginado(array $criterios, Builder $query = null): LengthAwarePaginator
    {
        $pageIndex = $criterios['pageIndex'] ?? 1;
        $pageSize = $criterios['pageSize'] ?? 10;
        $sortField = $criterios['sortField'];
        $sortOrder = $criterios['sortOrder'];
        $filters = $criterios['filters'];
        $searchTerm = $criterios['searchTerm'] ?? null;
        $searchColumn = $criterios['searchColumn'] ?? null;
        $query = $query ?? $this->modelo->query();

        // Aplicar filtros
        $this->aplicarFiltros($query, $filters);

        // Aplicar búsqueda
        $this->aplicarBusqueda($query, $searchTerm, $searchColumn);

        // Aplicar ordenamiento
        $this->aplicarOrdenamiento($query, $sortField, $sortOrder);
        // Paginar los resultados
        $paginados = $query->paginate($pageSize, ['*'], 'page', $pageIndex);
        if ($paginados->isEmpty()) {
            return $query->paginate($pageSize, ['*'], 'page', 1);
        }
        return $paginados;
    }

    /**
     * Verifica si es necesario hacer un join con una tabla.
     * Si no hay joins en la consulta, se asume que se necesita hacer un join.
     *
     * @param Builder $query
     * @param string $table
     * @return bool
     */
    protected function necesitaJoin(Builder $query, string $table): bool
    {
        if (!$query->getQuery()->joins) {
            return true;
        }
        foreach ($query->getQuery()->joins as $join) {
            if ($join->table == $table) {
                return false;
            }
        }

        return true;
    }

    /**
     * Une una tabla a la consulta.
     *
     * @param Builder $query
     * @param string $table
     * @param mixed ...$conditions
     */
    protected function unirTabla(Builder $query, string $table, ...$conditions): void
    {
        $query->join($table, ...$conditions);
    }

    /**
     * Aplica un join condicional a la consulta.
     *
     * @param Builder $query
     * @param string $table
     * @param mixed ...$conditions
     */
    protected function aplicarJoinCondicional(Builder $query, string $table, ...$conditions): void
    {
        if ($this->necesitaJoin($query, $table)) {
            $this->unirTabla($query, $table, ...$conditions);
        }
    }
}
