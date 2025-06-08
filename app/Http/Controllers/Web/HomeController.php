<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IPlanSuscripcionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected IPlanSuscripcionService $planSuscripcionService;

    public function __construct(IPlanSuscripcionService $planSuscripcionService)
    {
        $this->planSuscripcionService = $planSuscripcionService;
    }

    public function index(Request $request): View|Application|Factory
    {
        $intervalo = $request->get('intervalo', 'mes');
        $planes = $this->planSuscripcionService->obtenerPlanesSegunIntervalo($intervalo)
            ->where('activo', true)
            ->map(function ($plan) {
                // Asegurarse de que las características estén en el formato correcto
                if (is_string($plan->caracteristicas)) {
                    $plan->caracteristicas = json_decode($plan->caracteristicas, true);
                }
                return $plan;
            });

        return view('home', compact('planes'));
    }

    public function planes(Request $request): View|Application|Factory
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

        return view('planes.index', compact('planes'));
    }

    public function terminosCondiciones(): View|Application|Factory
    {
        return view('legal.terminos-condiciones');
    }

    public function politicaPrivacidad(): View|Application|Factory
    {
        return view('legal.politica-privacidad');
    }
}
