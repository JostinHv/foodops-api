<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IFacturaRepository extends IBaseRepository
{
    public function obtenerTodos(): Collection;

    public function obtenerPorId(int $id): ?Model;

    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): bool;

    public function eliminar(int $id): bool;

    public function obtenerPorSucursales(array $sucursalIds): Collection;

    public function obtenerPorOrden(int $ordenId): ?Model;

    public function obtenerUltimaFactura(): ?Model;
}
