<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IEstadoMesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstadoMesaController extends Controller
{
    private IEstadoMesaService $estadoMesaService;

    public function __construct(IEstadoMesaService $estadoMesaService)
    {
        $this->estadoMesaService = $estadoMesaService;
    }

    /**
     * Obtener listado de estados de mesa
     */
    public function index(): JsonResponse
    {
        try {
            $estados = $this->estadoMesaService->obtenerTodos()->where('activo', true);
            return ApiResponse::success($estados, 'Estados de mesa recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo estado de mesa
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $estado = $this->estadoMesaService->crear($request->all());
            return ApiResponse::success($estado, 'Estado de mesa creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un estado de mesa específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $estado = $this->estadoMesaService->obtenerPorId($id);

            if (!$estado) {
                return ApiResponse::error('Estado de mesa no encontrado', null, 404);
            }

            return ApiResponse::success($estado, 'Estado de mesa recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un estado de mesa existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->estadoMesaService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Estado de mesa no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Estado de mesa actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un estado de mesa
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->estadoMesaService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Estado de mesa no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Estado de mesa eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de un estado de mesa
     */
    public function cambiarActivoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->estadoMesaService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado de la mesa', null, 404);
            }

            return ApiResponse::success(null, 'Estado de la mesa actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }


}
