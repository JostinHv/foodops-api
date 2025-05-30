<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use App\Repositories\Interfaces\IItemMenuRepository;
use App\Repositories\Interfaces\IItemOrdenRepository;
use App\Repositories\Interfaces\IMesaRepository;
use App\Repositories\Interfaces\IOrdenRepository;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Interfaces\IMesaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class MesaService implements IMesaService
{

    public function __construct(
        private IMesaRepository               $repository,
        private IOrdenRepository              $ordenRepo,
        private IItemMenuRepository           $itemMenuRepo,
        private IItemOrdenRepository          $itemOrdenRepo,
        private IAsignacionPersonalRepository $asignacionPersonalRepo,
        private IUsuarioRepository            $usuarioRepo,
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

    public function obtenerMesasDisponibles(): Collection
    {
        return $this->repository->obtenerMesasDisponibles();
    }

    public function obtenerMesasPorSucursal(mixed $usuarioId)
    {
        $usuario = $this->usuarioRepo->obtenerPorId($usuarioId);
        if (!$usuario) {
            throw new \Exception('Usuario no encontrado');
        }

        $asignacionPersonal = $this->asignacionPersonalRepo->buscarPorUsuarioId($usuarioId);

        $sucursalId = $asignacionPersonal?->sucursal->id;
        return $this->repository->obtenerMesasPorSucursal($sucursalId)->map(function ($mesa) {
            return [
                'id' => $mesa->id,
                'nombre' => $mesa->nombre,
                'capacidad' => $mesa->capacidad,
                'estado' => $mesa->estadoMesa->nombre,
            ];
        });


    }

    public function cambiarEstadoMesa(int $id, mixed $estadoMesaId): bool
    {
        return $this->repository->cambiarEstadoMesa($id, $estadoMesaId);
    }
}
