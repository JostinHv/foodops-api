<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IPlanSuscripcionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanSuscripcionController extends Controller
{
    private IPlanSuscripcionService $planSuscripcionService;

    public function __construct(IPlanSuscripcionService $planSuscripcionService)
    {
        $this->planSuscripcionService = $planSuscripcionService;
    }

    /**
     * Obtener listado de planes de suscripción
     */
    public function index(): JsonResponse
    {
        try {
            $planes = $this->planSuscripcionService->obtenerTodos();
            return ApiResponse::success($planes, 'Planes de suscripción recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo plan de suscripción
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $plan = $this->planSuscripcionService->crear($request->all());
            return ApiResponse::success($plan, 'Plan de suscripción creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un plan de suscripción específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $plan = $this->planSuscripcionService->obtenerPorId($id);

            if (!$plan) {
                return ApiResponse::error('Plan de suscripción no encontrado', null, 404);
            }

            return ApiResponse::success($plan, 'Plan de suscripción recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un plan de suscripción existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->planSuscripcionService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Plan de suscripción no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Plan de suscripción actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un plan de suscripción
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->planSuscripcionService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Plan de suscripción no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Plan de suscripción eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de un plan de suscripción
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->planSuscripcionService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado del plan de suscripción', null, 404);
            }

            return ApiResponse::success(null, 'Estado del plan de suscripción actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
