<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IFacturaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    private IFacturaService $facturaService;

    public function __construct(IFacturaService $facturaService)
    {
        $this->facturaService = $facturaService;
    }

    /**
     * Obtener listado de facturas
     */
    public function index(): JsonResponse
    {
        try {
            $facturas = $this->facturaService->obtenerTodos();
            return ApiResponse::success($facturas, 'Facturas recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva factura
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $factura = $this->facturaService->crear($request->all());
            return ApiResponse::success($factura, 'Factura creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una factura específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $factura = $this->facturaService->obtenerPorId($id);

            if (!$factura) {
                return ApiResponse::error('Factura no encontrada', null, 404);
            }

            return ApiResponse::success($factura, 'Factura recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una factura existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->facturaService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Factura no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Factura actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una factura
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->facturaService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Factura no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Factura eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
