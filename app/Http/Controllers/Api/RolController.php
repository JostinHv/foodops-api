<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IRolService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolController extends Controller
{
    private IRolService $rolService;

    public function __construct(IRolService $rolService)
    {
        $this->rolService = $rolService;
    }

    /**
     * Obtener listado de roles
     */
    public function index(): JsonResponse
    {
        try {
            $roles = $this->rolService->obtenerTodos();
            return ApiResponse::success($roles, 'Roles recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo rol
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $rol = $this->rolService->crear($request->all());
            return ApiResponse::success($rol, 'Rol creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un rol específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $rol = $this->rolService->obtenerPorId($id);

            if (!$rol) {
                return ApiResponse::error('Rol no encontrado', null, 404);
            }

            return ApiResponse::success($rol, 'Rol recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un rol existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->rolService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Rol no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Rol actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un rol
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->rolService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Rol no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Rol eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
