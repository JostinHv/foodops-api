<?php

namespace App\Services\Implementations;

use App\Events\OrdenEvent;
use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use App\Repositories\Interfaces\IItemMenuRepository;
use App\Repositories\Interfaces\IItemOrdenRepository;
use App\Repositories\Interfaces\IOrdenRepository;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\IOrdenService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class OrdenService implements IOrdenService
{

    public function __construct(
        private IOrdenRepository              $repository,
        private IItemMenuRepository           $itemMenuRepo,
        private IItemOrdenRepository          $itemOrdenRepo,
        private IAsignacionPersonalRepository $asignacionPersonalRepo,
        private IUsuarioRepository            $usuarioRepo,
        private IMesaService                  $mesaService,
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

    public function generarNumeroOrden(int $sucursalId): int
    {
        try {
            $ultimoNumero = $this->repository->obtenerUltimoNumeroOrden($sucursalId);
            Log::info('Numero: ' . $ultimoNumero);
            if ($ultimoNumero) {
                return $ultimoNumero + 1;
            }
            return 1; // Si no hay órdenes, comenzamos con el número 1
        }catch (Exception $e){
            Log::error('Error al generar número de orden', [
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException('Error al generar número de orden: ' . $e->getMessage());
        }
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
            Log::info("Creando orden para usuario: {$usuario->nombres}, asignación: {$asignacionPersonal?->id}");
            $nroOrden = $this->generarNumeroOrden($asignacionPersonal->sucursal->id);
            Log::info('Nro de orden generado: ' . $nroOrden);
            $this->mesaService->actualizar($datos['mesa_id'], ['estado_mesa_id' => 2]); // Cambiar estado de la mesa a "Ocupada"
            $ordenData = [
                'tenant_id' => $usuario->tenant->id ?? null,
                'restaurante_id' => $usuario->restaurante->id ?? null,
                'sucursal_id' => $asignacionPersonal->sucursal->id ?? null,
                'mesa_id' => $datos['mesa_id'],
                'estado_orden_id' => 9,
                'mesero_id' => $usuarioId,
                'nro_orden' => $nroOrden,
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
            // Cargar las relaciones necesarias para el evento
            $orden->load(['estadoOrden', 'mesa', 'itemsOrdenes']);
            event(new OrdenEvent($orden, 'creada', [
                'mesero' => $usuario->nombre,
                'items_count' => count($itemsOrden)
            ]));

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

    public function obtenerPorSucursal(int $sucursalId): Collection
    {
        return $this->repository->obtenerPorSucursal($sucursalId);
    }

    /**
     * Marca una orden como servida
     */
    public function marcarComoServida(int $id): bool
    {
        try {
            DB::beginTransaction();

            $orden = $this->obtenerPorId($id);
            if (!$orden) {
                throw new \RuntimeException('Orden no encontrada');
            }

            // Actualizar el estado de la orden a "Servida" (estado_id = 4)
            $actualizado = $this->actualizar($id, ['estado_orden_id' => 4]);

            if (!$actualizado) {
                throw new \RuntimeException('Error al actualizar el estado de la orden');
            }

            DB::commit();

            // Cargar las relaciones necesarias para el evento
            $orden->load(['estadoOrden', 'mesa', 'itemsOrdenes']);

            // Disparar evento de orden servida
            event(new OrdenEvent($orden, 'servida'));

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al marcar orden como servida', [
                'orden_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function cambiarEstadoOrden(int $id, mixed $estado_orden_id): true
    {
        try {
            DB::beginTransaction();

            $orden = $this->obtenerPorId($id);
            if (!$orden) {
                throw new \RuntimeException('Orden no encontrada');
            }

            // Actualizar el estado de la orden
            $actualizado = $this->actualizar($id, ['estado_orden_id' => $estado_orden_id]);

            if (!$actualizado) {
                throw new \RuntimeException('Error al actualizar el estado de la orden');
            }

            DB::commit();

            // Cargar las relaciones necesarias para el evento
            $orden->load(['estadoOrden', 'mesa', 'itemsOrdenes']);

            // Disparar evento de cambio de estado
            event(new OrdenEvent($orden, 'estado_actualizado', [
                'estado_anterior' => $orden->estadoOrden->nombre
            ]));

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al cambiar estado de orden', [
                'orden_id' => $id,
                'estado_orden_id' => $estado_orden_id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function obtenerOrdenesPendientesPorSucursales(array $sucursalIds): Collection
    {
        return $this->repository->obtenerOrdenesPendientesPorSucursales($sucursalIds);
    }

    public function obtenerItemsOrden(int $ordenId): Collection
    {
        return $this->repository->obtenerItemsOrden($ordenId);
    }

    public function obtenerPorSucursalYFecha(int $sucursalId, string $fecha): Collection
    {
        return $this->repository->obtenerPorSucursalYFecha($sucursalId, $fecha);
    }

    public function obtenerPorSucursalFechaYEstado(int $sucursalId, string $fecha, ?int $estadoId = null): Collection
    {
        return $this->repository->obtenerPorSucursalFechaYEstado($sucursalId, $fecha, $estadoId);
    }
}
