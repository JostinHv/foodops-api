<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\ICategoriaMenuService;
use App\Services\Interfaces\IEstadoOrdenService;
use App\Services\Interfaces\IItemMenuService;
use App\Services\Interfaces\IItemOrdenService;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\IOrdenService;
use App\Services\Interfaces\IUsuarioService;
use App\Traits\AuthenticatedUserTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CocineroOrdenController extends Controller
{
    use AuthenticatedUserTrait;

    private IOrdenService $ordenService;
    private IItemMenuService $itemMenuService;
    private IMesaService $mesaService;
    private IItemOrdenService $itemOrdenService;
    private IEstadoOrdenService $estadoOrdenService;
    private IUsuarioService $usuarioService;
    private IAsignacionPersonalService $asignacionPersonalService;
    private ICategoriaMenuService $categoriaMenuService;

    public function __construct(
        IOrdenService              $ordenService,
        IItemMenuService           $itemMenuService,
        IMesaService               $mesaService,
        IItemOrdenService          $itemOrdenService,
        IEstadoOrdenService        $estadoOrdenService,
        IUsuarioService            $usuarioService,
        IAsignacionPersonalService $asignacionPersonalService,
        ICategoriaMenuService      $categoriaMenuService,
    )
    {
        $this->ordenService = $ordenService;
        $this->itemMenuService = $itemMenuService;
        $this->mesaService = $mesaService;
        $this->itemOrdenService = $itemOrdenService;
        $this->estadoOrdenService = $estadoOrdenService;
        $this->usuarioService = $usuarioService;
        $this->asignacionPersonalService = $asignacionPersonalService;
        $this->categoriaMenuService = $categoriaMenuService;
    }

    /**
     * Muestra la lista de órdenes
     */
    public function index(Request $request): View
    {
        $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
        $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuario->id);
        if (!$asignacionPersonal) {
            return view('login');
        }

        $sucursal = $asignacionPersonal->sucursal;
        $fechaSeleccionada = $request->get('fecha', now()->format('Y-m-d'));
        $estadoFiltro = $request->get('estado', 'Pendiente'); // Por defecto mostrar pendientes
        
        \Log::info('Fecha seleccionada: ' . $fechaSeleccionada);
        \Log::info('Estado filtro: ' . $estadoFiltro);
        
        // Obtener el ID del estado por nombre
        $estadoId = null;
        if ($estadoFiltro && $estadoFiltro !== '') {
            $estado = $this->estadoOrdenService->obtenerActivos()->firstWhere('nombre', $estadoFiltro);
            $estadoId = $estado ? $estado->id : null;
            \Log::info("Estado filtro: {$estadoFiltro}, ID encontrado: " . ($estadoId ?? 'null'));
        }
        
        $ordenes = $this->ordenService->obtenerPorSucursalFechaYEstado($sucursal->id, $fechaSeleccionada, $estadoId)
            ->filter(function ($orden) use ($usuario) {
                return $orden->tenant_id == $usuario->tenant_id;
            })
            ->map(function ($orden) {
                $orden->tiempo_transcurrido = [
                    'humano' => $orden->created_at->locale('es')->diffForHumans(['parts' => 1]),
                    'minutos' => $orden->created_at->isToday() ? (int)$orden->created_at->diffInMinutes() : null,
                    'es_hoy' => $orden->created_at->isToday()
                ];
                return $orden;
            });

        $estadosOrden = $this->estadoOrdenService->obtenerActivos();
        \Log::info("Cantidad de órdenes encontradas: " . $ordenes->count());
        return view('cocinero.orden', compact('ordenes', 'estadosOrden', 'fechaSeleccionada'));
    }

    /**
     * Ordena las órdenes según el criterio especificado
     */
    public function ordenar(Request $request): JsonResponse
    {
        try {
            $criterio = $request->input('criterio', 'reciente');
            $fecha = $request->input('fecha', now()->format('Y-m-d'));
            $estadoFiltro = $request->input('estado', '');

            $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
            $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuario->id);
            $sucursal = $asignacionPersonal->sucursal;

            // Obtener el ID del estado por nombre
            $estadoId = null;
            if ($estadoFiltro && $estadoFiltro !== '') {
                $estado = $this->estadoOrdenService->obtenerActivos()->firstWhere('nombre', $estadoFiltro);
                $estadoId = $estado ? $estado->id : null;
                \Log::info("Estado filtro (ordenar): {$estadoFiltro}, ID encontrado: " . ($estadoId ?? 'null'));
            }

            $ordenes = $this->ordenService->obtenerPorSucursalFechaYEstado($sucursal->id, $fecha, $estadoId)
                ->filter(function ($orden) use ($usuario) {
                    return $orden->tenant_id == $usuario->tenant_id;
                });

            // Aplicar ordenamiento según el criterio
            $ordenes = match ($criterio) {
                'reciente' => $ordenes->sortByDesc('created_at'),
                'antiguo' => $ordenes->sortBy('created_at'),
                'mesa' => $ordenes->sortBy('mesa.nombre'),
                default => $ordenes->sortByDesc('created_at'),
            };

            // Formatear las fechas y tiempos antes de enviar la respuesta
            $ordenesFormateadas = $ordenes->map(function ($orden) {
                $tiempoTranscurrido = [
                    'humano' => $orden->created_at->locale('es')->diffForHumans(['parts' => 1]),
                    'minutos' => $orden->created_at->isToday() ? (int)$orden->created_at->diffInMinutes() : null,
                    'es_hoy' => $orden->created_at->isToday()
                ];

                return [
                    'id' => $orden->id,
                    'nro_orden' => $orden->nro_orden,
                    'nombre_cliente' => $orden->nombre_cliente,
                    'estado_orden_id' => $orden->estado_orden_id,
                    'estado_orden' => [
                        'id' => $orden->estadoOrden->id,
                        'nombre' => $orden->estadoOrden->nombre
                    ],
                    'mesa' => [
                        'id' => $orden->mesa->id,
                        'nombre' => $orden->mesa->nombre
                    ],
                    'items_ordenes' => $orden->itemsOrdenes->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'cantidad' => $item->cantidad,
                            'monto' => $item->monto,
                            'item_menu' => [
                                'id' => $item->itemMenu->id,
                                'nombre' => $item->itemMenu->nombre,
                                'precio' => $item->itemMenu->precio
                            ]
                        ];
                    }),
                    'tiempo_transcurrido' => $tiempoTranscurrido,
                    'created_at' => $orden->created_at->toIso8601String()
                ];
            });

            return response()->json([
                'success' => true,
                'ordenes' => $ordenesFormateadas->values()->all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al ordenar las órdenes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra una orden específica
     */
    public function show(int $id): JsonResponse
    {
        $orden = $this->ordenService->obtenerPorId($id);
        if (!$orden) {
            return response()->json([
                'error' => 'Orden no encontrada'
            ], 404);
        }

        $orden->load(['mesa:id,nombre', 'estadoOrden:id,nombre', 'itemsOrdenes.itemMenu:id,nombre,precio']);

        // Calcular el tiempo transcurrido
        $tiempoTranscurrido = [
            'humano' => $orden->created_at->locale('es')->diffForHumans(['parts' => 1]),
            'minutos' => $orden->created_at->isToday() ? (int)$orden->created_at->diffInMinutes() : null,
            'es_hoy' => $orden->created_at->isToday()
        ];

        // Formatear la respuesta para incluir solo los datos necesarios
        $ordenFormateada = [
            'id' => $orden->id,
            'nro_orden' => $orden->nro_orden,
            'nombre_cliente' => $orden->nombre_cliente,
            'estado_orden' => [
                'id' => $orden->estadoOrden->id,
                'nombre' => $orden->estadoOrden->nombre
            ],
            'mesa' => [
                'id' => $orden->mesa->id,
                'nombre' => $orden->mesa->nombre
            ],
            'items_ordenes' => $orden->itemsOrdenes->map(function ($item) {
                return [
                    'id' => $item->id,
                    'cantidad' => $item->cantidad,
                    'monto' => $item->monto,
                    'item_menu' => [
                        'id' => $item->itemMenu->id,
                        'nombre' => $item->itemMenu->nombre,
                        'precio' => $item->itemMenu->precio
                    ]
                ];
            })
        ];

        return response()->json([
            'orden' => $ordenFormateada,
            'tiempo_transcurrido' => $tiempoTranscurrido
        ]);
    }


    /**
     * Marca una orden como servida
     */
    public function marcarServida(int $id): RedirectResponse
    {
        try {
            $orden = $this->ordenService->obtenerPorId($id);
            if (!$orden) {
                return redirect()
                    ->route('cocinero.orden.index')
                    ->with('error', 'Orden no encontrada');
            }

            $this->ordenService->marcarComoServida($id);

            return redirect()
                ->route('cocinero.orden.index')
                ->with('success', 'Orden marcada como servida exitosamente');

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al marcar la orden como servida. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Cambia el estado de una orden
     */
    public function cambiarEstado(string $id, Request $request): JsonResponse
    {
        try {
            $orden = $this->ordenService->obtenerPorId($id);
            if (!$orden) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orden no encontrada'
                ], 404);
            }

            $request->validate([
                'estado_orden_id' => 'required|exists:estados_ordenes,id'
            ]);

            $estadosQueLibenMesa = [
                3, // Entregada
                6, // Cancelada
                8  // Estado adicional que libera mesa
            ];

            if (in_array($request->estado_orden_id, $estadosQueLibenMesa, true)) {
                // Liberar la mesa (estado_mesa_id = 1 significa "Libre")
                $this->mesaService->actualizar($orden->mesa_id, ['estado_mesa_id' => 1]);
            }

            $this->ordenService->cambiarEstadoOrden($id, $request->estado_orden_id);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}
