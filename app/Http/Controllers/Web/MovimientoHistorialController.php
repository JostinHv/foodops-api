<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IMovimientoHistorialRepository;
use App\Services\Interfaces\IUsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovimientoHistorialController extends Controller
{
    protected IMovimientoHistorialRepository $movimientoHistorialRepository;
    protected IUsuarioService $usuarioService;

    public function __construct(IMovimientoHistorialRepository $movimientoHistorialRepository, IUsuarioService $usuarioService)
    {
        $this->movimientoHistorialRepository = $movimientoHistorialRepository;
        $this->usuarioService = $usuarioService;
    }

    public function index(Request $request)
    {
        $filtros = $request->only([
            'usuario_id',
            'tipo',
            'tabla_modificada',
            'fecha_inicio',
            'intervalo'
        ]);

        $porPagina = $request->input('por_pagina', 10);
        $ordenarPor = $request->input('ordenar_por', 'created_at');
        $orden = $request->input('orden', 'desc');

        $movimientos = $this->movimientoHistorialRepository->obtenerMovimientos(
            $filtros,
            $ordenarPor,
            $orden,
            $porPagina
        );

        return view('super-admin.movimientos.index', compact('movimientos'));
    }

    public function getUserDetail($id): JsonResponse
    {
        $usuario = $this->usuarioService->obtenerPorId((int)$id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Cargar relaciones necesarias (tenant, roles)
        $usuario->load(['tenant', 'roles']);

        return response()->json([
            'usuario' => $usuario
        ]);
    }
}
