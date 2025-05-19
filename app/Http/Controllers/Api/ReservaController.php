<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IReservaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    private IReservaService $reservaService;

    public function __construct(IReservaService $reservaService)
    {
        $this->reservaService = $reservaService;
    }

    /**
     * Obtener listado de reservas
     */
    public function index(): JsonResponse
    {
        try {
            $reservas = $this->reservaService->obtenerTodos();
            return ApiResponse::success($reservas, 'Reservas recuperadas exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear una nueva reserva
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $reserva = $this->reservaService->crear($request->all());
            return ApiResponse::success($reserva, 'Reserva creada exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener una reserva específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $reserva = $this->reservaService->obtenerPorId($id);

            if (!$reserva) {
                return ApiResponse::error('Reserva no encontrada', null, 404);
            }

            return ApiResponse::success($reserva, 'Reserva recuperada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar una reserva existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->reservaService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Reserva no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Reserva actualizada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar una reserva
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->reservaService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Reserva no encontrada', null, 404);
            }

            return ApiResponse::success(null, 'Reserva eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
