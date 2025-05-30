<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use App\Repositories\Interfaces\IItemMenuRepository;
use App\Repositories\Interfaces\IItemOrdenRepository;
use App\Repositories\Interfaces\IOrdenRepository;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Interfaces\IOrdenService;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Log;

readonly class OrdenService implements IOrdenService
{

    public function __construct(
        private IOrdenRepository              $repository,
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

    public function generarNumeroOrden(): int
    {
        $ultimoNumero = $this->repository->obtenerUltimoNumeroOrden();
        if ($ultimoNumero) {
            return $ultimoNumero + 1;
        }
        return 1; // Si no hay órdenes, comenzamos con el número 1
    }

    /**
     * @throws \Throwable
     */
    public function crearOrden(array $datos, mixed $usuarioId): Model
    {
        try {
            DB::beginTransaction();
            $usuario = $this->usuarioRepo->obtenerPorIdConRelaciones($usuarioId, ['tenant', 'restaurante']);
            $asignacionPersonal = $this->asignacionPersonalRepo->buscarPorUsuarioId($usuarioId);
            $ordenData = [
                'tenant_id' => $usuario->tenant->id ?? null,
                'restaurante_id' => $usuario->restaurante->id ?? null,
                'sucursal_id' => $asignacionPersonal->sucursal->id ?? null,
                'mesa_id' => $datos['mesa_id'],
                'mesero_id' => $usuarioId,
                'nro_orden' => $this->generarNumeroOrden(),
                'nombre_cliente' => $datos['cliente'],
                'tipo_servicio' => 'mesa',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $orden = $this->crear($ordenData);

            $itemsOrden = collect($datos['productos'])->map(function ($producto) use ($orden) {
                $itemMenu = $this->itemMenuRepo->obtenerPorId($producto['producto_id']);
                return [
                    'orden_id' => $orden->id,
                    'item_menu_id' => $itemMenu->id,
                    'cantidad' => $producto['cantidad'],
                    'monto' => $itemMenu->precio * $producto['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();

            $this->itemOrdenRepo->crearItemsOrden($itemsOrden);

            DB::commit();

            Log::info('Orden creada exitosamente', [
                'orden_id' => $orden->id,
                'mesero_id' => $usuarioId,
                'items' => count($itemsOrden)
            ]);

            return $orden;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al crear orden en el servicio', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function obtenerOrdenesPorSucursal(mixed $usuarioId): array
    {
        $usuario = $this->usuarioRepo->obtenerPorIdConRelaciones($usuarioId, ['tenant', 'restaurante']);
        if (!$usuario) {
            throw new \RuntimeException('Usuario no encontrado');
        }

        $asignacionPersonal = $this->asignacionPersonalRepo->buscarPorUsuarioId($usuarioId);

        $sucursalId = $asignacionPersonal?->sucursal->id;
        return $this->repository->obtenerOrdenesPorSucursal($sucursalId)->map(function ($orden) {
            return [
                'id' => $orden->id,
                'nro_orden' => $orden->nro_orden,
                'mesa' => $orden->mesa->nombre ?? 'Sin asignar',
                'cliente' => $orden->nombre_cliente,
                'fecha' => $orden->created_at->format('Y-m-d H:i:s'),
                'items' => $orden->itemsOrdenes?->count() ?? 0,
                'total' => $orden->itemsOrdenes?->sum('monto') ?? 0,
                'estado' => $orden->estadoOrden->nombre,
            ];
        })->toArray();

    }
}
