<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrearOrdenRequest;
use App\Services\Interfaces\IAsignacionPersonalService;
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

class OrdenController extends Controller
{
    use AuthenticatedUserTrait;

    private IOrdenService $ordenService;
    private IItemMenuService $itemMenuService;
    private IMesaService $mesaService;
    private IItemOrdenService $itemOrdenService;
    private IEstadoOrdenService $estadoOrdenService;
    private IUsuarioService $usuarioService;
    private IAsignacionPersonalService $asignacionPersonalService;

    public function __construct(
        IOrdenService              $ordenService,
        IItemMenuService           $itemMenuService,
        IMesaService               $mesaService,
        IItemOrdenService          $itemOrdenService,
        IEstadoOrdenService        $estadoOrdenService,
        IUsuarioService            $usuarioService,
        IAsignacionPersonalService $asignacionPersonalService,
    )
    {
        $this->ordenService = $ordenService;
        $this->itemMenuService = $itemMenuService;
        $this->mesaService = $mesaService;
        $this->itemOrdenService = $itemOrdenService;
        $this->estadoOrdenService = $estadoOrdenService;
        $this->usuarioService = $usuarioService;
        $this->asignacionPersonalService = $asignacionPersonalService;
    }

    /**
     * Muestra la lista de órdenes
     */
    public function index(): View
    {
        $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
        $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuario->id);
        if (!$asignacionPersonal) {
            return view('login');
        }
        $sucursal = $asignacionPersonal->sucursal;
        $ordenes = $this->ordenService->obtenerTodos()->where('tenant_id', $usuario->tenant_id)->where('sucursal_id', $sucursal->id)
            ->load(['mesa', 'estadoOrden', 'itemsOrdenes.itemMenu', 'mesero'])
            ->sortByDesc('created_at')
            ->map(function ($orden) {
                $orden->tiempo_transcurrido = [
                    'humano' => $orden->created_at->locale('es')->diffForHumans(['parts' => 1]),
                    'minutos' => $orden->created_at->isToday() ? (int)$orden->created_at->diffInMinutes() : null,
                    'es_hoy' => $orden->created_at->isToday()
                ];
                return $orden;
            });

        $estadosOrden = $this->estadoOrdenService->obtenerActivos();

        return view('mesero.orden', compact('ordenes', 'estadosOrden'));
    }

    /**
     * Ordena las órdenes según el criterio especificado
     */
    public function ordenar(Request $request): JsonResponse
    {
        try {
            $criterio = $request->input('criterio', 'reciente');
            $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
            $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuario->id);
            $sucursal = $asignacionPersonal->sucursal;
            $ordenes = $this->ordenService->obtenerTodos()->where('tenant_id', $usuario->tenant_id)->where('sucursal_id', $sucursal->id)
                ->load(['mesa', 'estadoOrden', 'itemsOrdenes.itemMenu', 'mesero']);

            // Aplicar ordenamiento según el criterio
            $ordenes = match ($criterio) {
                'reciente' => $ordenes->sortByDesc('created_at'),
                'antiguo' => $ordenes->sortBy('created_at'),
                'mesa' => $ordenes->sortBy('mesa.nombre'),
                default => $ordenes->sortByDesc('created_at'),
            };

            // Formatear las fechas y tiempos antes de enviar la respuesta
            $ordenesFormateadas = $ordenes->map(function ($orden) {
                $orden->tiempo_transcurrido = [
                    'humano' => $orden->created_at->locale('es')->diffForHumans(['parts' => 1]),
                    'minutos' => $orden->created_at->isToday() ? (int)$orden->created_at->diffInMinutes() : null,
                    'es_hoy' => $orden->created_at->isToday()
                ];
                $orden->created_at = $orden->created_at->toIso8601String();
                return $orden;
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
     * Muestra el formulario para crear una nueva orden
     */
    public function create(): View
    {
        $mesas = $this->mesaService->obtenerMesasDisponibles();
        $productos = $this->itemMenuService->obtenerTodosItemsDisponibles();

        // Agrupar productos por categoría
        $productosPorCategoria = $productos->groupBy('categoria_menu_id')
            ->map(function ($productos) {
                return [
                    'categoria' => $productos->first()->categoriaMenu,
                    'productos' => $productos
                ];
            });

        return view('mesero.nueva-orden', compact('mesas', 'productosPorCategoria'));
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

    public function store(CrearOrdenRequest $request): RedirectResponse
    {
        try {
            if (!$this->validateAuthenticatedUser()) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Debe iniciar sesión para realizar esta acción');
            }
            $this->mesaService->actualizar($request->mesa_id, ['estado_mesa_id' => 2]);
            $this->ordenService->crearOrden(
                $request->validated(),
                $this->getCurrentUser()->getAuthIdentifier()
            );

            return redirect()
                ->route('mesero.orden.index')
                ->with('success', 'Orden creada con éxito');

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear la orden. Por favor, inténtelo de nuevo.')
                ->withInput();
        }
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
                    ->route('mesero.orden.index')
                    ->with('error', 'Orden no encontrada');
            }

            $this->ordenService->marcarComoServida($id);

            return redirect()
                ->route('mesero.orden.index')
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

            if (in_array($request->estado_orden_id, $estadosQueLibenMesa)) {
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
