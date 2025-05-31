<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IEstadoMesaService;
use App\Services\Interfaces\IMesaService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MesaController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IMesaService       $mesaService,
        private readonly IEstadoMesaService $estadoMesaService,
    )
    {
    }

    public function index(): View
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $mesas = $this->mesaService->obtenerMesasPorSucursal($usuarioId);
        $totalMesas = count($mesas);
        $totalAsientos = $mesas->sum('capacidad');
        $mesasOcupadas = $mesas->where('estado', 'Ocupada')->count();
        $ocupacion = $totalMesas > 0 ? ($mesasOcupadas / $totalMesas) * 100 : 0;

        return view('gerente-sucursal.mesas', compact(
            'mesas',
            'totalMesas',
            'totalAsientos',
            'ocupacion'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();

        $request->validate([
            'nombre' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $this->mesaService->crearMesaPorSucursal($usuarioId, $request->only(['nombre', 'capacidad', 'descripcion']));
        return redirect()->back()->with('success', 'Mesa creada exitosamente');
    }

    public function cambiarEstado(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'estado_mesa_id' => 'required|exists:estados_mesas,id'
        ]);

        $actualizado = $this->mesaService->cambiarEstadoMesa($id, $request->input('estado_mesa_id'));

        if (!$actualizado) {
            return redirect()->back()
                ->withErrors(['error' => 'No se pudo actualizar el estado de la mesa']);
        }

        return redirect()->route('gerente.mesas')
            ->with('success', 'Estado de mesa actualizado exitosamente');
    }

    public function show(int $id): View
    {
        $mesa = $this->mesaService->obtenerPorId($id);
        if (!$mesa) {
            abort(404, 'Mesa no encontrada');
        }

        $estadosMesa = $this->estadoMesaService->obtenerActivos();

        return view('gerente-sucursal.mesas.show', compact('mesa', 'estadosMesa'));
    }

    public function edit(int $id): View
    {
        $mesa = $this->mesaService->obtenerPorId($id);
        if (!$mesa) {
            abort(404, 'Mesa no encontrada');
        }

        return view('gerente-sucursal.mesas.edit', compact('mesa'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $actualizado = $this->mesaService->actualizar($id, $request->only([
            'nombre', 'capacidad', 'descripcion'
        ]));

        if (!$actualizado) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'No se pudo actualizar la mesa']);
        }

        return redirect()->route('gerente.mesas')
            ->with('success', 'Mesa actualizada exitosamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        $eliminado = $this->mesaService->eliminar($id);

        if (!$eliminado) {
            return redirect()->back()
                ->withErrors(['error' => 'No se pudo eliminar la mesa']);
        }

        return redirect()->route('gerente.mesas')
            ->with('success', 'Mesa eliminada exitosamente');
    }

}
