<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IImagenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    private IImagenService $imagenService;

    public function __construct(IImagenService $imagenService)
    {
        $this->imagenService = $imagenService;
    }

    /**
     * Obtener listado de imágenes
     */
    public function index(): JsonResponse
    {
        try {
            $imagenes = $this->imagenService->obtenerTodos();
            return ApiResponse::success($imagenes, 'Imágenes recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva imagen
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $imagen = $this->imagenService->crear($request->all());
            return ApiResponse::success($imagen, 'Imagen creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una imagen específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $imagen = $this->imagenService->obtenerPorId($id);

            if (!$imagen) {
                return ApiResponse::error('Imagen no encontrada', null, 404);
            }

            return ApiResponse::success($imagen, 'Imagen recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una imagen existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->imagenService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Imagen no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Imagen actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una imagen
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->imagenService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Imagen no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Imagen eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de una imagen
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->imagenService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado de la imagen', null, 404);
            }

            return ApiResponse::success(null, 'Estado de la imagen actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
