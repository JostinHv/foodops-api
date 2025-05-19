<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IMesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    private IMesaService $mesaService;

    public function __construct(IMesaService $mesaService)
    {
        $this->mesaService = $mesaService;
    }

    /**
     * Obtener listado de mesas
     */
    public function index(): JsonResponse
    {
        try {
            $mesas = $this->mesaService->obtenerTodos();
            return ApiResponse::success($mesas, 'Mesas recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva mesa
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $mesa = $this->mesaService->crear($request->all());
            return ApiResponse::success($mesa, 'Mesa creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una mesa específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $mesa = $this->mesaService->obtenerPorId($id);

            if (!$mesa) {
                return ApiResponse::error('Mesa no encontrada', null, 404);
            }

            return ApiResponse::success($mesa, 'Mesa recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una mesa existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->mesaService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Mesa no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Mesa actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una mesa
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->mesaService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Mesa no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Mesa eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
