<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\ILimiteUsoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LimiteUsoController extends Controller
{
    private ILimiteUsoService $limiteUsoService;

    public function __construct(ILimiteUsoService $limiteUsoService)
    {
        $this->limiteUsoService = $limiteUsoService;
    }

    /**
     * Obtener listado de límites de uso
     */
    public function index(): JsonResponse
    {
        try {
            $limites = $this->limiteUsoService->obtenerTodos();
            return ApiResponse::success($limites, 'Límites de uso recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo límite de uso
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $limite = $this->limiteUsoService->crear($request->all());
            return ApiResponse::success($limite, 'Límite de uso creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un límite de uso específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $limite = $this->limiteUsoService->obtenerPorId($id);

            if (!$limite) {
                return ApiResponse::error('Límite de uso no encontrado', null, 404);
            }

            return ApiResponse::success($limite, 'Límite de uso recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un límite de uso existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->limiteUsoService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Límite de uso no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Límite de uso actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un límite de uso
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->limiteUsoService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Límite de uso no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Límite de uso eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
