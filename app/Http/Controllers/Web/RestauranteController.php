<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Imagen;
use App\Models\Restaurante;
use App\Services\Interfaces\IGrupoRestaurantesService;
use App\Services\Interfaces\IImagenService;
use App\Services\Interfaces\IRestauranteService;
use App\Services\Interfaces\IUsuarioService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestauranteController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IUsuarioService           $usuarioService,
        private readonly IRestauranteService       $restauranteService,
        private readonly IGrupoRestaurantesService $grupoRestaurantesService,
        private readonly IImagenService            $imagenService,
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $usuario = $this->usuarioService->obtenerPorId($usuarioId);
        $restaurantes = $this->restauranteService->obtenerRestaurantesPorTenant($usuario->tenant_id);
        $grupos = $this->grupoRestaurantesService->obtenerGrupoRestaurantesPorTenant($usuario->tenant_id);

        return view('admin-tenant.restaurants', compact('restaurantes', 'grupos'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'nombre_legal' => 'required|string|max:255',
                'grupo_restaurant_id' => 'nullable|exists:grupos_restaurantes,id',
                'nro_ruc' => 'nullable|string|max:11',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255',
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                'tipo_negocio' => 'nullable|string|max:255',
                'sitio_web_url' => 'nullable|url|max:255',
                'telefono' => 'nullable|string|max:20',
                'logo' => 'nullable|image|max:2048'
            ]);

            $data = $request->all();

            $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
            $usuario = $this->usuarioService->obtenerPorId($usuarioId);
            $tenant_id = $usuario->tenant_id;
            $data['tenant_id'] = $tenant_id;

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoPath = $logo->store('logos', 'public');

                $imagen = Imagen::create([
                    'url' => $logoPath,
                    'tipo' => 'logo',
                    'tenant_id' => $tenant_id,
                ]);

                $data['logo_id'] = $imagen->id;
            }

            $restaurante = $this->restauranteService->crear($data);
            return redirect()->route('tenant.restaurantes')
                ->with('success', 'Restaurante creado exitosamente');
        } catch (\Exception $exception) {
            \Log::log('info', 'Error al crear restaurante: ' . $exception->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el restaurante: ' . $exception->getMessage()])
                ->withInput();
        }

    }

    public function update(Request $request, Restaurante $restaurante): RedirectResponse
    {
        try {
            $request->validate([
                'nombre_legal' => 'required|string|max:255',
                'grupo_restaurant_id' => 'nullable|exists:grupos_restaurantes,id',
                'nro_ruc' => 'nullable|string|max:11',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255',
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                'tipo_negocio' => 'nullable|string|max:255',
                'sitio_web_url' => 'nullable|url|max:255',
                'telefono' => 'nullable|string|max:20',
                'logo' => 'nullable|image|max:2048'
            ]);

            $data = $request->all();

            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if ($restaurante->logo) {
                    Storage::disk('public')->delete($restaurante->logo->url);
                    $restaurante->logo->delete();
                }

                $logo = $request->file('logo');
                $logoPath = $logo->store('logos', 'public');

                $imagen = Imagen::create([
                    'url' => $logoPath,
                    'tipo' => 'logo',
                    'tenant_id' => $this->getCurrentUser()->tenant_id
                ]);

                $data['logo_id'] = $imagen->id;
            }

            $this->restauranteService->actualizar($restaurante->id, $data);

            return redirect()->route('tenant.restaurantes')
                ->with('success', 'Restaurante actualizado exitosamente');
        } catch (\Exception $exception) {
            \Log::error('Error al actualizar restaurante: ' . $exception->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el restaurante: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    public function toggleActivo(Restaurante $restaurante): RedirectResponse
    {
        try {
            $this->restauranteService->cambiarEstadoAutomatico($restaurante->id);
            return redirect()->route('tenant.restaurantes')
                ->with('success', 'Estado del restaurante actualizado exitosamente');
        } catch (\Exception $exception) {
            \Log::error('Error al cambiar estado del restaurante: ' . $exception->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al cambiar el estado del restaurante']);
        }
    }

    public function show(Restaurante $restaurante): \Illuminate\Http\JsonResponse
    {
        try {
            $restaurante->load(['grupoRestaurantes', 'logo']);
            return response()->json(['restaurante' => $restaurante]);
        } catch (\Exception $exception) {
            \Log::error('Error al obtener detalles del restaurante: ' . $exception->getMessage());
            return response()->json(['error' => 'Error al obtener los detalles del restaurante'], 500);
        }
    }
}
