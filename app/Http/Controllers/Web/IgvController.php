<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IIgvService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IgvController extends Controller
{
    private IIgvService $igvService;

    public function __construct(IIgvService $igvService)
    {
        $this->igvService = $igvService;
    }

    public function index()
    {
        $igvs = $this->igvService->obtenerTodos();
        return view('super-admin.igv', compact('igvs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'anio' => 'required|integer|min:2000|max:2100',
            'valor_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $datos = $request->all();
        $datos['valor_decimal'] = $datos['valor_porcentaje'] / 100;
        $datos['activo'] = true;

        $this->igvService->crear($datos);

        return redirect()->route('superadmin.igv')
            ->with('success', 'IGV creado exitosamente');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'anio' => 'required|integer|min:2000|max:2100',
            'valor_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $datos = $request->all();
        $datos['valor_decimal'] = $datos['valor_porcentaje'] / 100;

        $this->igvService->actualizar($id, $datos);

        return redirect()->route('superadmin.igv')
            ->with('success', 'IGV actualizado exitosamente');
    }

    public function toggleActivo($id): RedirectResponse
    {
        $this->igvService->cambiarEstadoAutomatico($id);
        return redirect()->route('superadmin.igv')
            ->with('success', 'Estado del IGV actualizado exitosamente');
    }
}
