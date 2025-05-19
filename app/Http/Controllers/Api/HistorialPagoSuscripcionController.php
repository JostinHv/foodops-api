<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IHistorialPagoSuscripcionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistorialPagoSuscripcionController extends Controller
{
    private IHistorialPagoSuscripcionService $historialPagoService;

    public function __construct(IHistorialPagoSuscripcionService $historialPagoService)
    {
        $this->historialPagoService = $historialPagoService;
    }

    /**
     * Obtener listado de historiales de pago
     */
    public function index(): JsonResponse
    {
        try {
            $historiales = $this->historialPagoService->obtenerTodos();
            return ApiResponse::success($historiales, 'Historiales de pago recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo historial de pago
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $historial = $this->historialPagoService->crear($request->all());
            return ApiResponse::success($historial, 'Historial de pago creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un historial de pago específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $historial = $this->historialPagoService->obtenerPorId($id);

            if (!$historial) {
                return ApiResponse::error('Historial de pago no encontrado', null, 404);
            }

            return ApiResponse::success($historial, 'Historial de pago recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un historial de pago existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->historialPagoService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Historial de pago no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Historial de pago actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un historial de pago
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->historialPagoService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Historial de pago no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Historial de pago eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
