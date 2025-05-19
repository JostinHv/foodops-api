<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IGrupoRestaurantesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrupoRestaurantesController extends Controller
{
    private IGrupoRestaurantesService $grupoRestaurantesService;

    public function __construct(IGrupoRestaurantesService $grupoRestaurantesService)
    {
        $this->grupoRestaurantesService = $grupoRestaurantesService;
    }

    /**
     * Obtener listado de grupos de restaurantes
     */
    public function index(): JsonResponse
    {
        try {
            $grupos = $this->grupoRestaurantesService->obtenerTodos();
            return ApiResponse::success($grupos, 'Grupos de restaurantes recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo grupo de restaurantes
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $grupo = $this->grupoRestaurantesService->crear($request->all());
            return ApiResponse::success($grupo, 'Grupo de restaurantes creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un grupo de restaurantes específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $grupo = $this->grupoRestaurantesService->obtenerPorId($id);

            if (!$grupo) {
                return ApiResponse::error('Grupo de restaurantes no encontrado', null, 404);
            }

            return ApiResponse::success($grupo, 'Grupo de restaurantes recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un grupo de restaurantes existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->grupoRestaurantesService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Grupo de restaurantes no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Grupo de restaurantes actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un grupo de restaurantes
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->grupoRestaurantesService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Grupo de restaurantes no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Grupo de restaurantes eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
