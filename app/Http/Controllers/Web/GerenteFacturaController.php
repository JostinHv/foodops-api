<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IFacturaService;
use App\Services\Interfaces\IIgvService;
use App\Services\Interfaces\IMetodoPagoService;
use App\Services\Interfaces\IOrdenService;
use App\Services\Interfaces\ISucursalService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class GerenteFacturaController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IFacturaService    $facturaService,
        private readonly IOrdenService      $ordenService,
        private readonly IIgvService        $igvService,
        private readonly IMetodoPagoService $metodoPagoService,
        private readonly ISucursalService   $sucursalService
    )
    {
    }

    public function index(): View
    {
        $usuarioActual = $this->getCurrentUser();
        $sucursales = $this->sucursalService->obtenerPorUsuarioId($usuarioActual->getAuthIdentifier());
        $sucursalIds = $sucursales->pluck('id')->toArray();

        $facturas = $this->facturaService->obtenerPorSucursales($sucursalIds);
        $ordenesPendientes = $this->ordenService->obtenerOrdenesPendientesPorSucursales($sucursalIds);
        $metodosPago = $this->metodoPagoService->obtenerActivos();
        $igvActivo = $this->igvService->obtenerActivo();
        return view('gerente-sucursal.facturacion', compact(
            'facturas',
            'ordenesPendientes',
            'metodosPago',
            'igvActivo'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'orden_id' => 'required|exists:ordenes,id',
                'metodo_pago_id' => 'required|exists:metodos_pagos,id',
                'igv_id' => 'required|exists:igv,id',
                'estado_pago' => 'required|string|in:pendiente,pagado,cancelado',
                'notas' => 'nullable|string'
            ]);

            // Obtener la orden y el IGV
            $orden = $this->ordenService->obtenerPorId($validated['orden_id']);
            $igv = $this->igvService->obtenerPorId($validated['igv_id']);

            if (!$orden || !$igv) {
                return response()->json([
                    'message' => 'Orden o IGV no encontrados'
                ], 404);
            }

            // Calcular montos
            $monto_total = $orden->itemsOrdenes->sum('monto');
            $monto_total_igv = $monto_total * ($igv->valor_decimal);

            // Preparar datos para crear la factura
            $datosFactura = array_merge($validated, [
                'monto_total' => $monto_total,
                'monto_total_igv' => $monto_total_igv,
                'fecha_pago' => $validated['estado_pago'] === 'pagado' ? now() : null,
                'hora_pago' => $validated['estado_pago'] === 'pagado' ? now() : null
            ]);

            $factura = $this->facturaService->crear($datosFactura);

            // Generar y actualizar número de factura
            $nro_factura = 'F' . str_pad($factura->id, 8, '0', STR_PAD_LEFT);
            $this->facturaService->actualizar($factura->id, ['nro_factura' => $nro_factura]);

            // Actualizar estado de la orden
            $this->ordenService->cambiarEstadoOrden($validated['orden_id'], 6); // 6 = Facturada

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

    public function destroy(int $id): JsonResponse
    {
        try {
            $factura = $this->facturaService->obtenerPorId($id);

            if (!$factura) {
                return response()->json([
                    'message' => 'Factura no encontrada'
                ], 404);
            }

            // Actualizar estado de la orden a pendiente
            $this->ordenService->cambiarEstadoOrden($factura->orden_id, 3); // 3 = Pendiente

            $this->facturaService->eliminar($id);

            return response()->json([
                'message' => 'Factura eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar factura: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al eliminar la factura'
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

    public function generarPDF($id)
    {
        $factura = $this->facturaService->obtenerPorId($id);
        
        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Cargar las relaciones necesarias
        $factura->load(['orden.itemsOrdenes.itemMenu', 'metodoPago', 'igv']);

        $pdf = PDF::loadView('pdf.factura', [
            'factura' => $factura,
            'items' => $factura->orden->itemsOrdenes,
            'subtotal' => $factura->monto_total,
            'igv' => $factura->monto_total_igv,
            'total' => $factura->monto_total + $factura->monto_total_igv
        ]);

        return $pdf->download("factura-{$factura->nro_factura}.pdf");
    }
}
