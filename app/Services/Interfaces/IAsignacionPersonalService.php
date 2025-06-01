<?php

namespace App\Services\Interfaces;

interface IAsignacionPersonalService
{
    public function crear(array $datos);

    public function actualizar(int $id, array $datos);

    public function obtenerPorId(int $id);

    public function obtenerPorUsuarioId(int $usuarioId);

    public function obtenerPorSucursalId(int $sucursalId);

    public function obtenerPorTenantId(int $tenantId);

    public function eliminar(int $id);

    public function cambiarEstado(int $id, int $activo);
}
