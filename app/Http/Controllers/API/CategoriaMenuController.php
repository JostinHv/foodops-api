<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ICategoriaMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoriaMenuController extends Controller
{
    protected ICategoriaMenuService $categoriaMenuService;

    public function __construct(ICategoriaMenuService $categoriaMenuService)
    {
        $this->categoriaMenuService = $categoriaMenuService;
    }

    public function index(): JsonResponse
    {
        $categorias = $this->categoriaMenuService->obtenerTodos();
        return response()->json(['data' => $categorias], ResponseAlias::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $categoria = $this->categoriaMenuService->obtenerPorId($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $categoria], ResponseAlias::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $categoria = $this->categoriaMenuService->crear($validated);
        return response()->json(['data' => $categoria], ResponseAlias::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        if (!$this->categoriaMenuService->actualizar($id, $validated)) {
            return response()->json(['message' => 'Categoría no encontrada'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Categoría actualizada correctamente'], ResponseAlias::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!$this->categoriaMenuService->eliminar($id)) {
            return response()->json(['message' => 'Categoría no encontrada'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }

    public function activos(): JsonResponse
    {
        $categorias = $this->categoriaMenuService->obtenerActivos();
        return response()->json(['data' => $categorias], ResponseAlias::HTTP_OK);
    }

    public function cambiarEstado(int $id): JsonResponse
    {
        if (!$this->categoriaMenuService->cambiarEstadoAutomatico($id)) {
            return response()->json(['message' => 'Categoría no encontrada'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Estado actualizado correctamente'], ResponseAlias::HTTP_OK);
    }

    public function ultimoActivo(): JsonResponse
    {
        $categoria = $this->categoriaMenuService->obtenerUltimoActivo();
        return response()->json(['data' => $categoria], ResponseAlias::HTTP_OK);
    }
}
