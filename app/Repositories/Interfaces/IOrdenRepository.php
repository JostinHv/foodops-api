<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IOrdenRepository extends IBaseRepository
{
    public function obtenerTodos(): Collection;

    public function obtenerPorId(int $id): ?Model;

    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): bool;

    public function eliminar(int $id): bool;

    public function obtenerPorSucursal(int $sucursalId): Collection;

    public function obtenerPorMesero(int $meseroId): Collection;

    public function obtenerPorEstado(int $estadoId): Collection;

    public function obtenerOrdenesPendientesPorSucursales(array $sucursalIds): Collection;

    public function obtenerItemsOrden(int $ordenId): Collection;

    public function obtenerUltimoNumeroOrden(): int;

    public function obtenerPorIdConRelaciones(int $id, array $relaciones = []): ?Model;

    public function obtenerOrdenesPorSucursal(mixed $sucursal_id);
}
