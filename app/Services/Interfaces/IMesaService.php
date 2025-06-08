<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IMesaService extends IBaseService
{
    public function obtenerTodos(): Collection;

    public function obtenerPorId(int $id): ?Model;

    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): bool;

    public function eliminar(int $id): bool;

    public function obtenerMesasDisponibles(): Collection;

    public function obtenerMesasPorSucursal(mixed $usuarioId);

    public function crearMesaPorSucursal(int $usuarioId, array $datos): Model;

    public function cambiarEstadoMesa(int $id, int $estadoMesaId);

    public function obtenerMesasDisponiblesPorSucursal(int $sucursalId): Collection;

}
