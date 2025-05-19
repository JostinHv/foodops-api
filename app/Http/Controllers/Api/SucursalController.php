<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\ISucursalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    private ISucursalService $sucursalService;

    public function __construct(ISucursalService $sucursalService)
    {
        $this->sucursalService = $sucursalService;
    }

    /**
     * Obtener listado de sucursales
     */
    public function index(): JsonResponse
    {
        try {
            $sucursales = $this->sucursalService->obtenerTodos();
            return ApiResponse::success($sucursales, 'Sucursales recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva sucursal
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $sucursal = $this->sucursalService->crear($request->all());
            return ApiResponse::success($sucursal, 'Sucursal creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una sucursal específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $sucursal = $this->sucursalService->obtenerPorId($id);

            if (!$sucursal) {
                return ApiResponse::error('Sucursal no encontrada', null, 404);
            }

            return ApiResponse::success($sucursal, 'Sucursal recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una sucursal existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->sucursalService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Sucursal no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Sucursal actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una sucursal
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->sucursalService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Sucursal no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Sucursal eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
