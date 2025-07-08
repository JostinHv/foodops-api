<?php

namespace App\Http\Controllers\Web;

use App\Events\OrdenEvent;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\IEstadoOrdenService;
use App\Services\Interfaces\IFacturaService;
use App\Services\Interfaces\IIgvService;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\IMetodoPagoService;
use App\Services\Interfaces\IOrdenService;
use App\Services\Interfaces\ISucursalService;
use App\Traits\AuthenticatedUserTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CajeroFacturaController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IFacturaService            $facturaService,
        private readonly IOrdenService              $ordenService,
        private readonly IIgvService                $igvService,
        private readonly IMetodoPagoService         $metodoPagoService,
        private readonly ISucursalService           $sucursalService,
        private readonly IAsignacionPersonalService $asignacionPersonalService,
        private readonly IEstadoOrdenService        $estadoOrdenService,
        private readonly IMesaService               $mesaService,
    )
    {
    }

    public function index(): View|RedirectResponse|JsonResponse
    {
        Log::info("CajeroFactura index");
        $usuarioActual = $this->getCurrentUser();
        $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuarioActual->getAuthIdentifier());

        if (!$asignacionPersonal) {
            return redirect()->route('login')->with('error', 'No tienes asignación de sucursal');
        }

        $sucursal = $asignacionPersonal->sucursal;

        // Obtener órdenes de la sucursal del cajero
        $ordenes = $this->ordenService->obtenerPorSucursal($sucursal->id)
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

        // Obtener órdenes pendientes de facturación (estados que permiten facturar)
        $ordenesPendientes = $ordenes->filter(function ($orden) {
            return in_array($orden->estado_orden_id, [1, 2, 3, 4, 5, 7, 8, 9, 10], true); // Pendiente, En Proceso, Preparada, Servida
        });

        Log::info('ordenesPendientes', ['count' => $ordenesPendientes->count()]);

        // Obtener facturas de la sucursal
        $facturas = $this->facturaService->obtenerPorSucursales([$sucursal->id]);

        $metodosPago = $this->metodoPagoService->obtenerActivos();
        $igvActivo = $this->igvService->obtenerActivo();
        $estadosOrden = $this->estadoOrdenService->obtenerActivos();

        // Si es una petición AJAX, devolver JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'ordenes' => $ordenes->values(),
                'facturas' => $facturas->values()
            ]);
        }

        return view('cajero.facturacion', compact(
            'ordenes',
            'ordenesPendientes',
            'facturas',
            'metodosPago',
            'igvActivo',
            'estadosOrden'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'orden_id' => 'required|exists:ordenes,id',
                'metodo_pago_id' => 'required|exists:metodos_pagos,id',
                'igv_id' => 'required|exists:igv,id',
                'notas' => 'nullable|string'
            ]);

            $validated['estado_pago'] = 'pagado';
            // Obtener la orden y el IGV
            $orden = $this->ordenService->obtenerPorId($validated['orden_id']);
            $igv = $this->igvService->obtenerPorId($validated['igv_id']);

            if (!$orden || !$igv) {
                return response()->json([
                    'message' => 'Orden o IGV no encontrados'
                ], 404);
            }

            // Calcular montos
            $monto_total = $mesaService->itemsOrdenes->sum('monto');
            $monto_total_igv = $monto_total * ($igv->valor_decimal) + $monto_total;

            // Preparar datos para crear la factura
            $datosFactura = array_merge($validated, [
                'monto_total' => $monto_total,
                'monto_total_igv' => $monto_total_igv,
                'fecha_pago' => now(),
                'hora_pago' => now()
            ]);

            $factura = $this->facturaService->crear($datosFactura);

            // Generar y actualizar número de factura
            $nro_factura = 'F' . str_pad($factura->id, 8, '0', STR_PAD_LEFT);
            $this->facturaService->actualizar($factura->id, ['nro_factura' => $nro_factura]);

            // Actualizar estado de la orden según el estado de pago
            $this->ordenService->cambiarEstadoOrden($validated['orden_id'], 6); // 6 = Pagada
            $this->mesaService->cambiarEstadoMesa($orden->mesa_id, 1); // Cambiar estado de la mesa a Libre = 1

            // Emitir evento de factura creada usando el evento de orden
            $factura->load(['orden.mesa', 'metodoPago', 'igv']);
            event(new OrdenEvent($factura->orden, 'factura_creada', [
                'factura' => [
                    'id' => $factura->id,
                    'nro_factura' => $factura->nro_factura,
                    'monto_total' => $factura->monto_total,
                    'monto_total_igv' => $factura->monto_total_igv,
                    'estado_pago' => $factura->estado_pago,
                    'metodo_pago' => $factura->metodoPago->nombre ?? 'N/A',
                    'igv' => $factura->igv->valor_porcentaje ?? '0'
                ]
            ]));

            return response()->json([
                'message' => 'Factura creada exitosamente',
                'factura' => $factura
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear factura: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear la factura'
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $factura = $this->facturaService->obtenerPorId($id);

            if (!$factura) {
                return response()->json([
                    'message' => 'Factura no encontrada'
                ], 404);
            }

            // Cargar las relaciones necesarias
            $factura->load(['orden.itemsOrdenes.itemMenu', 'metodoPago', 'igv', 'orden.mesa']);

            return response()->json([
                'factura' => $factura
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener factura: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener la factura'
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'metodo_pago_id' => 'required|exists:metodos_pagos,id',
                'estado_pago' => 'required|string|in:pendiente,pagado,cancelado',
                'notas' => 'nullable|string'
            ]);

            // Si el estado cambia a pagado, actualizar fecha y hora de pago
            if ($validated['estado_pago'] === 'pagado') {
                $validated['fecha_pago'] = now();
                $validated['hora_pago'] = now();
            } else {
                $validated['fecha_pago'] = null;
                $validated['hora_pago'] = null;
            }

            $this->facturaService->actualizar($id, $validated);

            // Emitir evento de factura actualizada
            $factura = $this->facturaService->obtenerPorId($id);
            $factura->load(['orden.mesa', 'metodoPago', 'igv']);

            event(new OrdenEvent($factura->orden, 'factura_actualizada', [
                'factura' => [
                    'id' => $factura->id,
                    'nro_factura' => $factura->nro_factura,
                    'monto_total' => $factura->monto_total,
                    'monto_total_igv' => $factura->monto_total_igv,
                    'estado_pago' => $factura->estado_pago,
                    'metodo_pago' => $factura->metodoPago->nombre ?? 'N/A',
                    'igv' => $factura->igv->valor_porcentaje ?? '0'
                ]
            ]));

            return response()->json([
                'message' => 'Factura actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar factura: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar la factura'
            ], 500);
        }
    }

    public function cambiarEstadoOrden(Request $request, int $ordenId): JsonResponse
    {
        try {
            $orden = $this->ordenService->obtenerPorId($ordenId);
            if (!$orden) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orden no encontrada'
                ], 404);
            }

            $request->validate([
                'estado_orden_id' => 'required|exists:estados_ordenes,id'
            ]);

            $this->ordenService->cambiarEstadoOrden($ordenId, $request->estado_orden_id);

            // Recargar la orden para obtener el nuevo estado
            $ordenActualizada = $this->ordenService->obtenerPorId($ordenId);
            $ordenActualizada->load('estadoOrden');


            return response()->json([
                'success' => true,
                'message' => 'Estado de orden actualizado correctamente',
                'nuevo_estado' => $ordenActualizada->estadoOrden->nombre
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de orden: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calcularTotales(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'orden_id' => 'required|exists:ordenes,id',
                'igv_id' => 'required|exists:igv,id'
            ]);

            $orden = $this->ordenService->obtenerPorId($validated['orden_id']);
            $igv = $this->igvService->obtenerPorId($validated['igv_id']);

            if (!$orden || !$igv) {
                return response()->json([
                    'message' => 'Orden o IGV no encontrados'
                ], 404);
            }

            $subtotal = $orden->itemsOrdenes->sum('monto');
            $montoIgv = $subtotal * $igv->valor_decimal;
            $total = $subtotal + $montoIgv;

            return response()->json([
                'subtotal' => $subtotal,
                'monto_igv' => $montoIgv,
                'total' => $total,
                'igv_porcentaje' => $igv->valor_porcentaje
            ]);
        } catch (\Exception $e) {
            Log::error('Error al calcular totales: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al calcular los totales'
            ], 500);
        }
    }

    public function generarPDF($id): Response|JsonResponse
    {
        try {
            $factura = $this->facturaService->obtenerPorId($id);

            if (!$factura) {
                return response()->json(['message' => 'Factura no encontrada'], 404);
            }

            // Cargar las relaciones necesarias
            $factura->load(['orden.itemsOrdenes.itemMenu', 'metodoPago', 'igv']);

            // Obtener información del restaurante y sucursal
            $sucursal = $factura->orden->sucursal;
            $restaurante = $sucursal->restaurante;

            $pdf = PDF::loadView('pdf.factura', [
                'factura' => $factura,
                'items' => $factura->orden->itemsOrdenes,
                'subtotal' => $factura->monto_total,
                'igv' => $factura->monto_total_igv,
                'total' => $factura->monto_total + $factura->monto_total_igv,
                'restaurante' => $restaurante,
                'sucursal' => $sucursal
            ]);

            // Configurar el PDF
            $pdf->setPaper('a4');
            $pdf->setOption('margin-top', 10);
            $pdf->setOption('margin-right', 10);
            $pdf->setOption('margin-bottom', 10);
            $pdf->setOption('margin-left', 10);

            return $pdf->download("factura-{$factura->nro_factura}.pdf");
        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            return response()->json(['message' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
        }
    }

    public function generarPDFPOS($id): Response|JsonResponse
    {
        try {
            $factura = $this->facturaService->obtenerPorId($id);

            if (!$factura) {
                return response()->json(['message' => 'Factura no encontrada'], 404);
            }

            // Cargar las relaciones necesarias
            $factura->load(['orden.itemsOrdenes.itemMenu', 'metodoPago', 'igv']);

            // Obtener información del restaurante y sucursal
            $sucursal = $factura->orden->sucursal;
            $restaurante = $sucursal->restaurante;

            $pdf = PDF::loadView('pdf.factura-pos', [
                'factura' => $factura,
                'items' => $factura->orden->itemsOrdenes,
                'subtotal' => $factura->monto_total,
                'igv' => $factura->monto_total_igv,
                'total' => $factura->monto_total + $factura->monto_total_igv,
                'restaurante' => $restaurante,
                'sucursal' => $sucursal
            ]);

            // Configurar el PDF para impresión térmica
            $pdf->setPaper([0, 0, 300, 1000]); // Tamaño personalizado para ticket
            $pdf->setOption('margin-top', 0);
            $pdf->setOption('margin-right', 0);
            $pdf->setOption('margin-bottom', 0);
            $pdf->setOption('margin-left', 0);
            $pdf->setOption('dpi', 72);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);

            return $pdf->download("ticket-{$factura->nro_factura}.pdf");
        } catch (\Exception $e) {
            Log::error('Error al generar PDF POS: ' . $e->getMessage());
            return response()->json(['message' => 'Error al generar el ticket: ' . $e->getMessage()], 500);
        }
    }

    public function showOrden(int $ordenId): JsonResponse
    {
        try {
            $orden = $this->ordenService->obtenerPorId($ordenId);

            if (!$orden) {
                return response()->json([
                    'message' => 'Orden no encontrada'
                ], 404);
            }

            // Cargar las relaciones necesarias
            $orden->load(['mesa:id,nombre', 'estadoOrden:id,nombre', 'itemsOrdenes.itemMenu:id,nombre,precio']);

            // Calcular tiempo transcurrido
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
                'created_at' => $orden->created_at->toIso8601String(),
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
                        'cantidad' => (int)$item->cantidad,
                        'monto' => (float)$item->monto,
                        'item_menu' => [
                            'id' => $item->itemMenu->id,
                            'nombre' => $item->itemMenu->nombre,
                            'precio' => (float)$item->itemMenu->precio
                        ]
                    ];
                })
            ];

            return response()->json([
                'orden' => $ordenFormateada,
                'tiempo_transcurrido' => $tiempoTranscurrido
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener orden: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener la orden'
            ], 500);
        }
    }

    /**
     * Obtener órdenes actualizadas para AJAX
     */
    public function getOrdenesActualizadas(): JsonResponse
    {
        try {
            $usuarioActual = $this->getCurrentUser();
            $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuarioActual->getAuthIdentifier());

            if (!$asignacionPersonal) {
                return response()->json(['error' => 'No tienes asignación de sucursal'], 403);
            }

            $sucursal = $asignacionPersonal->sucursal;

            // Obtener órdenes de la sucursal del cajero
            $ordenes = $this->ordenService->obtenerPorSucursal($sucursal->id)
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

            return response()->json([
                'success' => true,
                'ordenes' => $ordenes->values()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener órdenes actualizadas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las órdenes'
            ], 500);
        }
    }

    /**
     * Obtener facturas actualizadas para AJAX
     */
    public function getFacturasActualizadas(): JsonResponse
    {
        try {
            $usuarioActual = $this->getCurrentUser();
            $asignacionPersonal = $this->asignacionPersonalService->obtenerPorUsuarioId($usuarioActual->getAuthIdentifier());

            if (!$asignacionPersonal) {
                return response()->json(['error' => 'No tienes asignación de sucursal'], 403);
            }

            $sucursal = $asignacionPersonal->sucursal;

            // Obtener facturas de la sucursal con todas las relaciones necesarias
            $facturas = $this->facturaService->obtenerPorSucursales([$sucursal->id])
                ->load([
                    'orden.mesa',
                    'orden.itemsOrdenes.itemMenu',
                    'metodoPago',
                    'igv'
                ])
                ->sortByDesc('created_at');

            return response()->json([
                'success' => true,
                'facturas' => $facturas->values()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener facturas actualizadas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las facturas'
            ], 500);
        }
    }

}
