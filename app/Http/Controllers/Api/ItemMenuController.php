<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IItemMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemMenuController extends Controller
{
    private IItemMenuService $itemMenuService;

    public function __construct(IItemMenuService $itemMenuService)
    {
        $this->itemMenuService = $itemMenuService;
    }

    /**
     * Obtener listado de items del menú
     */
    public function index(): JsonResponse
    {
        try {
            $items = $this->itemMenuService->obtenerTodos();
            return ApiResponse::success($items, 'Items del menú recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo item del menú
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $item = $this->itemMenuService->crear($request->all());
            return ApiResponse::success($item, 'Item del menú creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un item del menú específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->itemMenuService->obtenerPorId($id);

            if (!$item) {
                return ApiResponse::error('Item del menú no encontrado', null, 404);
            }

            return ApiResponse::success($item, 'Item del menú recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un item del menú existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->itemMenuService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Item del menú no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Item del menú actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un item del menú
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->itemMenuService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Item del menú no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Item del menú eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
