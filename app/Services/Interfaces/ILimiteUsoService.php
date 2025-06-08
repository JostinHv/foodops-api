<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ILimiteUsoService extends IBaseService
{
    public function obtenerTodos(): Collection;

    public function obtenerPorId(int $id): ?Model;

    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): bool;

    public function eliminar(int $id): bool;

    public function crearLimiteRecursoTodos(int $tenantSuscriptionId, array $limitesMaximos): bool;

    public function modificarLimiteUsoPorTipoRecurso(int $tenantSuscriptionId, string $tipoRecurso, array $datos): bool;

    public function modificarLimitesPorSuscripcionesIds(array $suscripcionesIds, array $limites);

    public function esstaUsoMaximoRecurso(int $tenantSuscripcion, string $tipoRecurso): bool;

}
