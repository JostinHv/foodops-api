<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class UsuarioService implements IUsuarioService
{

    public function __construct(
        private IUsuarioRepository         $repository,
        private IAsignacionPersonalService $asignacionPersonalService,
        private ISucursalService           $sucursalService,
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

    public function obtenerUsuariosOperativosPorTenantId(int $tenantId): Collection
    {
        return $this->repository->obtenerPorTenantId($tenantId);
    }

    public function obtenerTodosPorTenantId(int $tenantId): Collection
    {
        return $this->repository->obtenerTodosPorTenantId($tenantId);
    }


    public function obtenerPorEmail(string $email): ?Model
    {
        return $this->repository->obtenerPorEmail($email);
    }

    public function estaBloqueado(int $usuarioId): bool
    {
        $usuario = $this->repository->obtenerPorId($usuarioId);
        if (!$usuario) {
            return false;
        }
        if (!($usuario->activo)) {
            return true;
        }
        if (!($usuario->tenant?->activo ?? true)) {
            return true;
        }
        return false;
    }

    public function tieneAcceso(Model $usuario): bool
    {
        $rol = $usuario->roles->first();
        if ($rol->nombre === 'gerente') {
            return $this->sucursalService->gerenteTieneSucursal($usuario->id);
        }
        return true;
    }
}
