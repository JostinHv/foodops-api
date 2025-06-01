<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioService;
use App\Services\Interfaces\IRestauranteService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IUsuarioService     $usuarioService,
        private readonly ISucursalService    $sucursalService,
        private readonly IRestauranteService $restauranteService,
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $usuario = $this->usuarioService->obtenerPorId($usuarioId);
        $sucursales = $this->sucursalService->obtenerTodos();
        $restaurantes = $this->restauranteService->obtenerRestaurantesPorTenant($usuario->tenant_id);
        $gerentes = $this->usuarioService->obtenerPorTenantId($usuario->tenant_id);

        // Cargar relaciones
        if ($sucursales) {
            $sucursales->load(['restaurante', 'usuario']);
        }

        return view('admin-tenant.sucursales', compact('sucursales', 'restaurantes', 'gerentes'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'restaurante_id' => 'required|exists:restaurantes,id',
                'usuario_id' => 'nullable|exists:usuarios,id',
                'nombre' => 'required|string|max:255',
                'tipo' => 'nullable|string|max:50',
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'capacidad_total' => 'nullable|integer|min:0',
                'hora_apertura' => 'nullable|date_format:H:i',
                'hora_cierre' => 'nullable|date_format:H:i'
            ]);

            $data = $request->all();
            $data['activo'] = true;

            $this->sucursalService->crear($data);
            return redirect()->route('tenant.sucursales')
                ->with('success', 'Sucursal creada exitosamente');
        } catch (\Exception $exception) {
            \Log::error('Error al crear sucursal: ' . $exception->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear la sucursal: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Sucursal $sucursal): RedirectResponse
    {
        try {
            $request->validate([
                'restaurante_id' => 'required|exists:restaurantes,id',
                'usuario_id' => 'nullable|exists:usuarios,id',
                'nombre' => 'required|string|max:255',
                'tipo' => 'nullable|string|max:50',
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'capacidad_total' => 'nullable|integer|min:0',
                'hora_apertura' => 'nullable|date_format:H:i',
                'hora_cierre' => 'nullable|date_format:H:i'
            ]);

            $data = $request->all();
            $this->sucursalService->actualizar($sucursal->id, $data);

            return redirect()->route('tenant.sucursales')
                ->with('success', 'Sucursal actualizada exitosamente');
        } catch (\Exception $exception) {
            \Log::error('Error al actualizar sucursal: ' . $exception->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar la sucursal: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    public function show(Sucursal $sucursal): \Illuminate\Http\JsonResponse
    {
        try {
            $sucursal->load(['restaurante', 'usuario']);
            return response()->json(['sucursal' => $sucursal]);
        } catch (\Exception $exception) {
            \Log::error('Error al obtener detalles de la sucursal: ' . $exception->getMessage());
            return response()->json(['error' => 'Error al obtener los detalles de la sucursal'], 500);
        }
    }

    public function toggleActivo(Sucursal $sucursal): RedirectResponse
    {
        try {
            $this->sucursalService->cambiarEstadoAutomatico($sucursal->id);
            return redirect()->route('tenant.sucursales')
                ->with('success', 'Estado de la sucursal actualizado exitosamente');
        } catch (\Exception $exception) {
            \Log::error('Error al cambiar estado de la sucursal: ' . $exception->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al cambiar el estado de la sucursal']);
        }
    }
}
