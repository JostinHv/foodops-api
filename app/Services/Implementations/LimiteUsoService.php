<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ILimiteUsoRepository;
use App\Services\Interfaces\ILimiteUsoService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class LimiteUsoService implements ILimiteUsoService
{

    public function __construct(
        private ILimiteUsoRepository $repository
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

    public function crearLimiteRecursoTodos(int $tenantSuscriptionId, array $limitesMaximos): bool
    {
        if (isset($limitesMaximos['usuarios'])) {
            $this->repository->crearLimiteUsoUsuario($tenantSuscriptionId, $limitesMaximos['usuarios']);
        }
        if (isset($limitesMaximos['restaurantes'])) {
            $this->repository->crearLimiteUsoRestaurante($tenantSuscriptionId, $limitesMaximos['restaurantes']);
        }
        if (isset($limitesMaximos['sucursales'])) {
            $this->repository->crearLimiteUsoSucursal($tenantSuscriptionId, $limitesMaximos['sucursales']);
        }
        return true;
    }

    public function modificarLimiteUsoPorTipoRecurso(int $tenantSuscriptionId, string $tipoRecurso, array $datos): bool
    {
        return match ($tipoRecurso) {
            'usuario' => $this->repository->modificarLimiteUsoUsuario($tenantSuscriptionId, $datos),
            'restaurante' => $this->repository->modificarLimiteUsoRestaurante($tenantSuscriptionId, $datos),
            'sucursal' => $this->repository->modificarLimiteUsoSucursal($tenantSuscriptionId, $datos),
            default => throw new \InvalidArgumentException("Tipo de recurso no válido: {$tipoRecurso}"),
        };
    }

    public function modificarLimitesPorSuscripcionesIds(array $suscripcionesIds, array $limites): true
    {
        foreach ($suscripcionesIds as $id) {
            $limites = match ($limites) {
                'usuarios' => ['tipo_recurso' => 'usuario', 'limite_maximo' => $limites['usuarios']],
                'restaurantes' => ['tipo_recurso' => 'restaurante', 'limite_maximo' => $limites['restaurantes']],
                'sucursales' => ['tipo_recurso' => 'sucursal', 'limite_maximo' => $limites['sucursales']],
                default => throw new \InvalidArgumentException("Tipo de recurso no válido: {$limites}"),
            };
            $this->modificarLimiteUsoPorTipoRecurso($id, $limites['tipo_recurso'], ['limite_maximo' => $limites['limite_maximo']]);
        }
        return true;
    }

    public function esstaUsoMaximoRecurso(int $tenantSuscripcion, string $tipoRecurso): bool
    {
        return $this->repository->estaElUsoAlMaximoDelRecurso($tenantSuscripcion, $tipoRecurso);
    }
}
