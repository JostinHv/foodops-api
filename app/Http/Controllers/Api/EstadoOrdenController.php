<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IEstadoOrdenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstadoOrdenController extends Controller
{
    private IEstadoOrdenService $estadoOrdenService;

    public function __construct(IEstadoOrdenService $estadoOrdenService)
    {
        $this->estadoOrdenService = $estadoOrdenService;
    }

    /**
     * Obtener listado de estados de orden
     */
    public function index(): JsonResponse
    {
        try {
            $estados = $this->estadoOrdenService->obtenerTodos();
            return ApiResponse::success($estados, 'Estados de orden recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo estado de orden
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $estado = $this->estadoOrdenService->crear($request->all());
            return ApiResponse::success($estado, 'Estado de orden creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un estado de orden específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $estado = $this->estadoOrdenService->obtenerPorId($id);

            if (!$estado) {
                return ApiResponse::error('Estado de orden no encontrado', null, 404);
            }

            return ApiResponse::success($estado, 'Estado de orden recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un estado de orden existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->estadoOrdenService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Estado de orden no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Estado de orden actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un estado de orden
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->estadoOrdenService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Estado de orden no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Estado de orden eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de un estado de orden
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->estadoOrdenService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado de la orden', null, 404);
            }

            return ApiResponse::success(null, 'Estado de la orden actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
