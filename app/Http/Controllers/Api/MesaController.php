<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IMesaService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    private IMesaService $mesaService;
    use AuthenticatedUserTrait;

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

    public function obtenerMesasPorSucursal(): JsonResponse
    {
        try {
            $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
            $mesas = $this->mesaService->obtenerMesasPorSucursal($usuarioId);
            return ApiResponse::success($mesas, 'Mesas recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado de una mesa
     */
    public function cambiarEstadoMesa(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'estado_mesa_id' => 'required|integer|exists:estados_mesas,id',
            ]);
            $estadoMesaId = $request->input('estado_mesa_id');
            $actualizado = $this->mesaService->cambiarEstadoMesa($id, $estadoMesaId);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado de la mesa', null, 404);
            }

            return ApiResponse::success(null, 'Estado de la mesa actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

}
