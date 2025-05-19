<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IMetodoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    private IMetodoPagoService $metodoPagoService;

    public function __construct(IMetodoPagoService $metodoPagoService)
    {
        $this->metodoPagoService = $metodoPagoService;
    }

    /**
     * Obtener listado de métodos de pago
     */
    public function index(): JsonResponse
    {
        try {
            $metodosPago = $this->metodoPagoService->obtenerTodos();
            return ApiResponse::success($metodosPago, 'Métodos de pago recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo método de pago
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $metodoPago = $this->metodoPagoService->crear($request->all());
            return ApiResponse::success($metodoPago, 'Método de pago creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un método de pago específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $metodoPago = $this->metodoPagoService->obtenerPorId($id);

            if (!$metodoPago) {
                return ApiResponse::error('Método de pago no encontrado', null, 404);
            }

            return ApiResponse::success($metodoPago, 'Método de pago recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un método de pago existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->metodoPagoService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Método de pago no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Método de pago actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un método de pago
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->metodoPagoService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Método de pago no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Método de pago eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de un método de pago
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->metodoPagoService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado del método de pago', null, 404);
            }

            return ApiResponse::success(null, 'Estado del método de pago actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
