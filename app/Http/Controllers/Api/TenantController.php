<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\ITenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    private ITenantService $tenantService;

    public function __construct(ITenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Obtener listado de tenants
     */
    public function index(): JsonResponse
    {
        try {
            $tenants = $this->tenantService->obtenerTodos();
            return ApiResponse::success($tenants, 'Tenants recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo tenant
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $tenant = $this->tenantService->crear($request->all());
            return ApiResponse::success($tenant, 'Tenant creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un tenant específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tenant = $this->tenantService->obtenerPorId($id);

            if (!$tenant) {
                return ApiResponse::error('Tenant no encontrado', null, 404);
            }

            return ApiResponse::success($tenant, 'Tenant recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un tenant existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->tenantService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Tenant no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Tenant actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un tenant
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->tenantService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Tenant no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Tenant eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
