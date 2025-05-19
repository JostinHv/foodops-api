<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IIgvService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IgvController extends Controller
{
    private IIgvService $igvService;

    public function __construct(IIgvService $igvService)
    {
        $this->igvService = $igvService;
    }

    /**
     * Obtener listado de IGVs
     */
    public function index(): JsonResponse
    {
        try {
            $igvs = $this->igvService->obtenerTodos();
            return ApiResponse::success($igvs, 'IGVs recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo IGV
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $igv = $this->igvService->crear($request->all());
            return ApiResponse::success($igv, 'IGV creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un IGV específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $igv = $this->igvService->obtenerPorId($id);

            if (!$igv) {
                return ApiResponse::error('IGV no encontrado', null, 404);
            }

            return ApiResponse::success($igv, 'IGV recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un IGV existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->igvService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('IGV no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'IGV actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un IGV
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->igvService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('IGV no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'IGV eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de un IGV
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->igvService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado del IGV', null, 404);
            }

            return ApiResponse::success(null, 'Estado del IGV actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
