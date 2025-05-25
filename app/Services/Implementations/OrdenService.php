<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IOrdenRepository;
use App\Services\Interfaces\IOrdenService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class OrdenService implements IOrdenService
{

    public function __construct(
        private IOrdenRepository $repository
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

    public function generarNumeroOrden(): int
    {
        $ultimoNumero = $this->repository->obtenerUltimoNumeroOrden();
        if ($ultimoNumero) {
            return $ultimoNumero + 1;
        }
        return 1; // Si no hay órdenes, comenzamos con el número 1
    }
}
