<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GrupoRestaurantes;
use App\Services\Interfaces\IGrupoRestaurantesService;
use App\Services\Interfaces\IUsuarioService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GrupoRestauranteController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IUsuarioService           $usuarioService,
        private readonly IGrupoRestaurantesService $grupoRestaurantesService,
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $usuario = $this->usuarioService->obtenerPorId($usuarioId);
        $grupos = $this->grupoRestaurantesService->obtenerGrupoRestaurantesPorTenant($usuario->tenant_id);
        // Cargar la relación restaurantes
        if ($grupos) {
            $grupos->load('restaurantes');
        }
        return view('admin-tenant.grupo-restaurant', compact('grupos'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string'
            ]);

            $data = $request->all();
            $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
            $usuario = $this->usuarioService->obtenerPorId($usuarioId);
            $data['tenant_id'] = $usuario->tenant_id;

            $this->grupoRestaurantesService->crear($data);
            return redirect()->route('tenant.grupo-restaurant')
                ->with('success', 'Grupo de restaurantes creado exitosamente');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el grupo de restaurantes: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, GrupoRestaurantes $grupo): RedirectResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string'
            ]);

            $data = $request->all();
            $this->grupoRestaurantesService->actualizar($grupo->id, $data);

            return redirect()->route('tenant.grupo-restaurant')
                ->with('success', 'Grupo de restaurantes actualizado exitosamente');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el grupo de restaurantes: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    public function show(GrupoRestaurantes $grupo): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(['grupo' => $grupo]);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Error al obtener los detalles del grupo'], 500);
        }
    }
}
