<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ILimiteUsoRepository
{
    public function obtenerTodos(): Collection;

    public function obtenerPorId(int $id): ?Model;

    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): bool;

    public function eliminar(int $id): bool;

    public function crearLimiteUsoUsuario(int $tenantSuscriptionId, int $limiteMax): ?Model;

    public function crearLimiteUsoRestaurante(int $tenantSuscriptionId, int $limiteMax): ?Model;

    public function crearLimiteUsoSucursal(int $tenantSuscriptionId, int $limiteMax): ?Model;

    public function modificarLimiteUsoUsuario(int $tenantSuscriptionId, array $datos): bool;

    public function modificarLimiteUsoRestaurante(int $tenantSuscriptionId, array $datos): bool;

    public function modificarLimiteUsoSucursal(int $tenantSuscriptionId, array $datos): bool;

    public function estaElUsoAlMaximoDelRecurso(int $tenantSuscripcion, string $tipoRecurso): bool;

}
