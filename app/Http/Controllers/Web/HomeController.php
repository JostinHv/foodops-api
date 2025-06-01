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

        $planes = $this->planSuscripcionService->obtenerPlanesSegunIntervalo($intervalo)->where('activo', true);

        return view('home', compact('planes'));
    }
}
