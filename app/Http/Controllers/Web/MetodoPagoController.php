<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IMetodoPagoService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    public function __construct(
        private readonly IMetodoPagoService $metodoPagoService
    ) {
    }

    public function index(): View|Application|Factory
    {
        $metodosPago = $this->metodoPagoService->obtenerTodos();
        return view('super-admin.pago', compact('metodosPago'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $this->metodoPagoService->crear($request->all());

        return redirect()->route('superadmin.pago')
            ->with('success', 'Método de pago creado exitosamente.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $this->metodoPagoService->actualizar($id, $request->all());

        return redirect()->route('superadmin.pago')
            ->with('success', 'Método de pago actualizado exitosamente.');
    }

    public function toggleActivo(int $id): RedirectResponse
    {
        $this->metodoPagoService->cambiarEstadoAutomatico($id);

        return redirect()->route('superadmin.pago')
            ->with('success', 'Estado del método de pago actualizado exitosamente.');
    }
}
