<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IEstadoCajaRepository;
use App\Services\Interfaces\IEstadoCajaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class EstadoCajaService implements IEstadoCajaService
{
    public function __construct(private IEstadoCajaRepository $repository) {}

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
} 