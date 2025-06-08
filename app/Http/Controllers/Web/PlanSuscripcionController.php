<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PlanSuscripcion;
use App\Services\Interfaces\ILimiteUsoService;
use App\Services\Interfaces\IPlanSuscripcionService;
use App\Services\Interfaces\ITenantService;
use App\Services\Interfaces\ITenantSuscripcionService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanSuscripcionController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IPlanSuscripcionService   $planSuscripcionService,
        private readonly ITenantService            $tenantService,
        private readonly ITenantSuscripcionService $tenantSuscripcionService,
        private readonly ILimiteUsoService         $limiteUsoService,
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $planes = $this->planSuscripcionService->obtenerTodos();
        $planesMensuales = $this->planSuscripcionService->obtenerPlanesSegunIntervalo('mes');
        $planesAnuales = $this->planSuscripcionService->obtenerPlanesSegunIntervalo('anual');

        // Obtener estadísticas
        $estadisticas = [
            'total_planes' => $planes->count(),
            'planes_activos' => $planes->where('activo', true)->count(),
            'planes_mensuales' => $planesMensuales->count(),
            'planes_anuales' => $planesAnuales->count(),
            'tenants_por_plan' => $this->obtenerEstadisticasTenantsPorPlan($planes),
            'ingresos_totales' => $this->calcularIngresosTotales($planes),
            'ingresos_mensuales' => $this->calcularIngresosPorIntervalo($planesMensuales),
            'ingresos_anuales' => $this->calcularIngresosPorIntervalo($planesAnuales),
        ];

        return view('super-admin.planes', compact('planes', 'planesMensuales', 'planesAnuales', 'estadisticas'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'intervalo' => 'required|in:mes,anual',
                'limite_usuarios' => 'required|integer|min:0',
                'limite_restaurantes' => 'required|integer|min:0',
                'limite_sucursales' => 'required|integer|min:0',
                'caracteristicas' => 'required|string|min:1',
            ]);

            $data = $request->all();

            // Convertir la cadena de características en array y limpiar espacios
            $caracteristicasArray = array_filter(array_map('trim', explode(',', $data['caracteristicas'])));

            // Validar que haya al menos una característica
            if (empty($caracteristicasArray)) {
                return redirect()->back()
                    ->withErrors(['caracteristicas' => 'Debe agregar al menos una característica al plan'])
                    ->withInput();
            }

            // Crear el array de características con los tipos correctos
            $caracteristicas = [
                'limites' => [
                    'usuarios' => (int)$data['limite_usuarios'],
                    'restaurantes' => (int)$data['limite_restaurantes'],
                    'sucursales' => (int)$data['limite_sucursales']
                ],
                'adicionales' => $caracteristicasArray
            ];

            // Asignar el array directamente, Laravel se encargará de la serialización
            $data['caracteristicas'] = $caracteristicas;

            $this->planSuscripcionService->crear($data);
            return redirect()->route('planes')
                ->with('success', 'Plan creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear el plan: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el plan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, PlanSuscripcion $plan): RedirectResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'intervalo' => 'required|in:mes,anual',
                'limite_usuarios' => 'required|integer|min:0',
                'limite_restaurantes' => 'required|integer|min:0',
                'limite_sucursales' => 'required|integer|min:0',
                'caracteristicas' => 'required|string|min:1',
            ]);

            $data = $request->all();

            // Convertir la cadena de características en array y limpiar espacios
            $caracteristicasArray = array_filter(array_map('trim', explode(',', $data['caracteristicas'])));

            // Validar que haya al menos una característica
            if (empty($caracteristicasArray)) {
                return redirect()->back()
                    ->withErrors(['caracteristicas' => 'Debe agregar al menos una característica al plan'])
                    ->withInput();
            }

            // Crear el array de características con los tipos correctos
            $caracteristicas = [
                'limites' => [
                    'usuarios' => (int)$data['limite_usuarios'],
                    'restaurantes' => (int)$data['limite_restaurantes'],
                    'sucursales' => (int)$data['limite_sucursales']
                ],
                'adicionales' => $caracteristicasArray
            ];

            // Asignar el array directamente, Laravel se encargará de la serialización
            $data['caracteristicas'] = $caracteristicas;

            $this->planSuscripcionService->actualizar($plan->id, $data);

            $suscripcionesIds = $this->tenantSuscripcionService->obtenerTenantsPorPlan($plan->id)->pluck('id')->toArray();
            if (!empty($suscripcionesIds)) {
                $this->limiteUsoService->modificarLimitesPorSuscripcionesIds($suscripcionesIds, $caracteristicas['limites']);
            }
            return redirect()->route('planes')
                ->with('success', 'Plan actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el plan: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el plan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function toggleActivo(PlanSuscripcion $plan): \Illuminate\Http\JsonResponse
    {
        try {
            $this->planSuscripcionService->cambiarEstadoAutomatico($plan->id);
            return response()->json([
                'success' => true,
                'activo' => !$plan->activo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del plan'
            ], 500);
        }
    }

    private function obtenerEstadisticasTenantsPorPlan($planes): array
    {
        $estadisticas = [];
        foreach ($planes as $plan) {
            $tenantsSuscripciones = $this->tenantSuscripcionService->obtenerTenantsPorPlan($plan->id);
            $estadisticas[$plan->id] = [
                'nombre' => $plan->nombre,
                'total' => $tenantsSuscripciones->count(),
                'activos' => $tenantsSuscripciones->where('activo', true)->count(),
            ];
        }
        return $estadisticas;
    }

    private function calcularIngresosTotales($planes): float
    {
        $ingresos = 0;
        foreach ($planes as $plan) {
            $tenantsSuscripciones = $this->tenantSuscripcionService->obtenerTenantsPorPlan($plan->id);
            $ingresos += $tenantsSuscripciones->count() * $plan->precio;
        }
        return $ingresos;
    }

    private function calcularIngresosPorIntervalo($planes): float
    {
        $ingresos = 0;
        foreach ($planes as $plan) {
            $tenantsSuscripciones = $this->tenantSuscripcionService->obtenerTenantsPorPlan($plan->id);
            $ingresos += $tenantsSuscripciones->count() * $plan->precio;
        }
        return $ingresos;
    }

    public function show(PlanSuscripcion $plan): \Illuminate\Http\JsonResponse
    {
        try {
            // Decodificar las características JSON
            $caracteristicas = $plan->caracteristicas;
            $caracteristicasString = implode(', ', $caracteristicas['adicionales'] ?? []);

            // Crear un array con la estructura necesaria
            $planData = [
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'descripcion' => $plan->descripcion,
                'precio' => $plan->precio,
                'intervalo' => $plan->intervalo,
                'activo' => $plan->activo,
                'limite_usuarios' => $caracteristicas['limites']['usuarios'] ?? 0,
                'limite_restaurantes' => $caracteristicas['limites']['restaurantes'] ?? 0,
                'limite_sucursales' => $caracteristicas['limites']['sucursales'] ?? 0,
                'caracteristicas' => $caracteristicasString,
            ];


            return response()->json([
                'success' => true,
                'plan' => $planData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos del plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
