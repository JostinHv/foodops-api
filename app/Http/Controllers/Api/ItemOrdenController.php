<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IItemOrdenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemOrdenController extends Controller
{
    private IItemOrdenService $itemOrdenService;

    public function __construct(IItemOrdenService $itemOrdenService)
    {
        $this->itemOrdenService = $itemOrdenService;
    }

    /**
     * Obtener listado de items de orden
     */
    public function index(): JsonResponse
    {
        try {
            $items = $this->itemOrdenService->obtenerTodos();
            return ApiResponse::success($items, 'Items de orden recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo item de orden
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $item = $this->itemOrdenService->crear($request->all());
            return ApiResponse::success($item, 'Item de orden creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un item de orden específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->itemOrdenService->obtenerPorId($id);

            if (!$item) {
                return ApiResponse::error('Item de orden no encontrado', null, 404);
            }

            return ApiResponse::success($item, 'Item de orden recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un item de orden existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->itemOrdenService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Item de orden no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Item de orden actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un item de orden
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->itemOrdenService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Item de orden no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Item de orden eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
