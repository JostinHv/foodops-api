<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IRestauranteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    private IRestauranteService $restauranteService;

    public function __construct(IRestauranteService $restauranteService)
    {
        $this->restauranteService = $restauranteService;
    }

    /**
     * Obtener listado de restaurantes
     */
    public function index(): JsonResponse
    {
        try {
            $restaurantes = $this->restauranteService->obtenerTodos();
            return ApiResponse::success($restaurantes, 'Restaurantes recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo restaurante
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $restaurante = $this->restauranteService->crear($request->all());
            return ApiResponse::success($restaurante, 'Restaurante creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un restaurante específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $restaurante = $this->restauranteService->obtenerPorId($id);

            if (!$restaurante) {
                return ApiResponse::error('Restaurante no encontrado', null, 404);
            }

            return ApiResponse::success($restaurante, 'Restaurante recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un restaurante existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->restauranteService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Restaurante no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Restaurante actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un restaurante
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->restauranteService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Restaurante no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Restaurante eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
