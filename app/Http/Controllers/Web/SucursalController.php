<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\IRestauranteService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioService;
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
        private readonly IUsuarioService            $usuarioService,
        private readonly ISucursalService           $sucursalService,
        private readonly IRestauranteService        $restauranteService,
        private readonly IAsignacionPersonalService $asignacionPersonalService,
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $usuario = $this->usuarioService->obtenerPorId($usuarioId);
        $sucursales = $this->sucursalService->obtenerTodos();
        $restaurantes = $this->restauranteService->obtenerRestaurantesPorTenant($usuario->tenant_id);
        $gerentes = $this->usuarioService->obtenerUsuariosOperativosPorTenantId($usuario->tenant_id);
        $gerentes = $gerentes->filter(function ($gerente) {
            return $gerente->roles->contains('nombre', 'gerente');
        });

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
                'usuario_id' => 'required|exists:usuarios,id',
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

            $this->usuarioService->actualizar(
                $data['usuario_id'],
                ['restaurante_id' => $data['restaurante_id']]
            );

            $usuario = $this->usuarioService->obtenerPorId($data['usuario_id']);

            $sucursal = $this->sucursalService->crear($data);

            $this->asignacionPersonalService->asignarUsuarioSucursal($usuario, $sucursal->id);

            return redirect()->route('tenant.sucursales')
                ->with('success', 'Sucursal creada exitosamente');
        } catch (\Exception $exception) {
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

            $this->usuarioService->actualizar(
                $data['usuario_id'],
                ['restaurante_id' => $data['restaurante_id']]
            );

            $this->sucursalService->actualizar($sucursal->id, $data);

            $usuario = $this->usuarioService->obtenerPorId($data['usuario_id']);

            $this->asignacionPersonalService->asignarUsuarioSucursal($usuario, $sucursal->id);

            return redirect()->route('tenant.sucursales')
                ->with('success', 'Sucursal actualizada exitosamente');
        } catch (\Exception $exception) {
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
            return redirect()->back()
                ->withErrors(['error' => 'Error al cambiar el estado de la sucursal']);
        }
    }
}
