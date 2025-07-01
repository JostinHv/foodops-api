<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IAsignacionPersonalService
{
    public function crear(array $datos);

    public function actualizar(int $id, array $datos);

    public function obtenerPorId(int $id);

    public function obtenerPorUsuarioId(int $usuarioId);

    public function obtenerPorSucursalId(int $sucursalId);

    public function obtenerPorTenantId(int $tenantId): Collection;

    public function eliminar(int $id);

    public function cambiarEstado(int $id, int $activo);

    public function asignarUsuarioSucursal(Model $usuario, int $sucursalId): bool;

    public function obtenerAsignacionGerenteAsignadoSucursal(int $sucursalId): ?Model;
}
