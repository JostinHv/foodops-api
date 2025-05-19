<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IAsignacionPersonalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AsignacionPersonalController extends Controller
{
    private IAsignacionPersonalService $asignacionPersonalService;

    public function __construct(IAsignacionPersonalService $asignacionPersonalService)
    {
        $this->asignacionPersonalService = $asignacionPersonalService;
    }

    /**
     * Obtener listado de asignaciones de personal
     */
    public function index(): JsonResponse
    {
        try {
            $asignaciones = $this->asignacionPersonalService->obtenerTodos();
            return ApiResponse::success($asignaciones, 'Asignaciones de personal recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva asignación de personal
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $asignacion = $this->asignacionPersonalService->crear($request->all());
            return ApiResponse::success($asignacion, 'Asignación de personal creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una asignación de personal específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $asignacion = $this->asignacionPersonalService->obtenerPorId($id);

            if (!$asignacion) {
                return ApiResponse::error('Asignación de personal no encontrada', null, 404);
            }

            return ApiResponse::success($asignacion, 'Asignación de personal recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una asignación de personal existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->asignacionPersonalService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Asignación de personal no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Asignación de personal actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una asignación de personal
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->asignacionPersonalService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Asignación de personal no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Asignación de personal eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de una asignación de personal
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->asignacionPersonalService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado de la asignación', null, 404);
            }

            return ApiResponse::success(null, 'Estado de la asignación actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
