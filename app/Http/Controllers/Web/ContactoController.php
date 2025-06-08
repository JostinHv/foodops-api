<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IPlanSuscripcionService;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    protected IPlanSuscripcionService $planSuscripcionService;

    public function __construct(IPlanSuscripcionService $planSuscripcionService)
    {
        $this->planSuscripcionService = $planSuscripcionService;
    }

    /**
     * Muestra el formulario de contacto para planes
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function mostrarFormulario(Request $request)
    {
        $intervalo = $request->get('intervalo', 'mes');
        $planes = $this->planSuscripcionService->obtenerPlanesSegunIntervalo($intervalo)
            ->where('activo', true)
            ->map(function ($plan) {
                if (is_string($plan->caracteristicas)) {
                    $plan->caracteristicas = json_decode($plan->caracteristicas, true);
                }
                return $plan;
            });

        return view('contacto.planes', compact('planes', 'intervalo'));
    }
}
