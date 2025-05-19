<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\ICategoriaMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaMenuController extends Controller
{
    private ICategoriaMenuService $categoriaMenuService;

    public function __construct(ICategoriaMenuService $categoriaMenuService)
    {
        $this->categoriaMenuService = $categoriaMenuService;
    }

    /**
     * Obtener listado de categorías de menú
     */
    public function index(): JsonResponse
    {
        try {
            $categorias = $this->categoriaMenuService->obtenerTodos();
            return ApiResponse::success($categorias, 'Categorías de menú recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva categoría de menú
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $categoria = $this->categoriaMenuService->crear($request->all());
            return ApiResponse::success($categoria, 'Categoría de menú creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una categoría de menú específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $categoria = $this->categoriaMenuService->obtenerPorId($id);

            if (!$categoria) {
                return ApiResponse::error('Categoría de menú no encontrada', null, 404);
            }

            return ApiResponse::success($categoria, 'Categoría de menú recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una categoría de menú existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->categoriaMenuService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Categoría de menú no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Categoría de menú actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una categoría de menú
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->categoriaMenuService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Categoría de menú no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Categoría de menú eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de una categoría de menú
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->categoriaMenuService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado de la categoría', null, 404);
            }

            return ApiResponse::success(null, 'Estado de categoría actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
