<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IGrupoRestaurantesRepository;
use App\Services\Interfaces\IGrupoRestaurantesService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class GrupoRestaurantesService implements IGrupoRestaurantesService
{

    public function __construct(
        private IGrupoRestaurantesRepository $repository
    )
    {
    }

    public function obtenerTodos(): Collection
    {
        return $this->repository->obtenerTodos();
    }

    public function obtenerPorId(int $id): ?Model
    {
        return $this->repository->obtenerPorId($id);
    }

    public function crear(array $datos): Model
    {
        return $this->repository->crear($datos);
    }

    public function actualizar(int $id, array $datos): bool
    {
        return $this->repository->actualizar($id, $datos);
    }

    public function eliminar(int $id): bool
    {
        return $this->repository->eliminar($id);
    }

    public function obtenerGrupoRestaurantesPorTenant(mixed $tenant_id): Collection
    {
        return $this->repository->obtenerGrupoRestaurantesPorTenant($tenant_id);
    }
}
