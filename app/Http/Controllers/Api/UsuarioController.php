<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Interfaces\IUsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private IUsuarioService $usuarioService;

    public function __construct(IUsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    /**
     * Obtener listado de usuarios
     */
    public function index(): JsonResponse
    {
        try {
            $usuarios = $this->usuarioService->obtenerTodos();
            return ApiResponse::success($usuarios, 'Usuarios recuperados exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->crear($request->all());
            return ApiResponse::success($usuario, 'Usuario creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Obtener un usuario específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->obtenerPorId($id);
            if (!$usuario) {
                return ApiResponse::error('Usuario no encontrado', null, 404);
            }

            return ApiResponse::success($usuario, 'Usuario recuperado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Actualizar un usuario existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $actualizado = $this->usuarioService->actualizar($id, $request->all());

            if (!$actualizado) {
                return ApiResponse::error('Usuario no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $eliminado = $this->usuarioService->eliminar($id);

            if (!$eliminado) {
                return ApiResponse::error('Usuario no encontrado', null, 404);
            }

            return ApiResponse::success(null, 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Cambiar estado automático de un usuario
     */
    public function cambiarEstadoAutomatico(int $id): JsonResponse
    {
        try {
            $actualizado = $this->usuarioService->cambiarEstadoAutomatico($id);

            if (!$actualizado) {
                return ApiResponse::error('No se pudo cambiar el estado del usuario', null, 404);
            }

            return ApiResponse::success(null, 'Estado del usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
