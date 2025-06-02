<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IIgvRepository;
use App\Services\Interfaces\IIgvService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class IgvService implements IIgvService
{

    public function __construct(
        private IIgvRepository $repository
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
        // Si el nuevo IGV será activo, desactivar todos los demás
        if ($datos['activo']) {
            $this->repository->desactivarTodos();
        }

        return $this->repository->crear($datos);
    }

    public function actualizar(int $id, array $datos): bool
    {
        // Si se está activando este IGV, desactivar todos los demás
        if (isset($datos['activo']) && $datos['activo']) {
            $this->repository->desactivarTodos();
        }

        return $this->repository->actualizar($id, $datos);
    }

    public function cambiarEstadoAutomatico(int $id): bool
    {
        $igv = $this->repository->obtenerPorId($id);

        if (!$igv) {
            return false;
        }

        // Si el IGV está inactivo, desactivar todos los demás antes de activarlo
        if (!$igv->activo) {
            $this->repository->desactivarTodos();
        }

        return $this->repository->cambiarEstadoAutomatico($id);
    }

    public function obtenerActivo(): ?Model
    {
        return $this->repository->obtenerActivo();
    }

    public function eliminar(int $id): bool
    {
        return $this->repository->eliminar($id);
    }

    public function cambiarEstado(int $id, int $activo): bool
    {
        return $this->repository->cambiarEstado($id, $activo);
    }

    public function obtenerActivos(): Collection
    {
        return $this->repository->obtenerActivos();
    }

    public function obtenerUltimoActivo(): Collection
    {
        return $this->repository->obtenerUltimoActivo();
    }

    public function desactivarTodos(): bool
    {
        return $this->repository->desactivarTodos();
    }
}
