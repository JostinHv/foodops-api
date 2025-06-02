<?php

namespace App\Services\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IOrdenService extends IBaseService
{
    public function obtenerTodos(): Collection;

    public function obtenerPorId(int $id): ?Model;

    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): bool;

    public function eliminar(int $id): bool;

    public function generarNumeroOrden(): int;

    public function crearOrden(array $datos, mixed $usuarioId);

    public function obtenerOrdenesPorSucursal(mixed $usuarioId);

    public function marcarComoServida(int $id): bool;

    public function cambiarEstadoOrden(int $id, mixed $estado_orden_id);
}
