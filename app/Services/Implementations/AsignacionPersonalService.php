<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use App\Services\Interfaces\IAsignacionPersonalService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

readonly class AsignacionPersonalService implements IAsignacionPersonalService
{

    public function __construct(
        private IAsignacionPersonalRepository $repository
    )
    {
    }

    public function cambiarEstadoAutomatico(int $id): bool
    {
        return $this->repository->cambiarEstadoAutomatico($id);
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

    public function obtenerPorUsuarioId(int $usuarioId): ?Model
    {
        return $this->repository->obtenerPorUsuarioId($usuarioId);
    }

    public function obtenerPorSucursalId(int $sucursalId): Collection
    {
        return $this->repository->obtenerPorSucursalId($sucursalId);
    }

    public function obtenerPorTenantId(int $tenantId): Collection
    {
        return $this->repository->obtenerPorTenantId($tenantId);
    }

    public function asignarUsuarioSucursal(?Model $usuario, mixed $sucursalId): bool
    {
        try {
            if ($usuario) {
                $asignacion = $this->repository->obtenerPorUsuarioId($usuario->id);
                if ($asignacion) {
                    return $this->repository->actualizar($asignacion->id, [
                        'tenant_id' => $usuario->tenant_id ?? null,
                        'sucursal_id' => $sucursalId ?? null,
                        'tipo' => $usuario->roles->first()->nombre ?? null,
                        'fecha_asignacion' => now(),
                        'updated_at' => now()
                    ]);
                }

                $asignacionNueva = $this->repository->crear([
                    'tenant_id' => $usuario->tenant_id,
                    'usuario_id' => $usuario->id,
                    'sucursal_id' => $sucursalId,
                    'tipo' => $usuario->roles->first()->nombre ?? null,
                    'fecha_asignacion' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return true;
            }
            return false;
        } catch (\Exception $exception) {
            Log::error('Error al asignar usuario a sucursal: ' . $exception->getMessage());
            return false;
        }
    }

    public function obtenerAsignacionGerenteAsignadoSucursal(int $sucursalId): ?Model
    {
        try {
            $asignacion = $this->repository->obtenerPorSucursalId($sucursalId)
                ->where('tipo', 'gerente')
                ->first();
            return $asignacion ?: null;
        } catch (\Exception $exception) {
            Log::error('Error al verificar gerente asignado a sucursal: ' . $exception->getMessage());
            return null;
        }
    }
}
