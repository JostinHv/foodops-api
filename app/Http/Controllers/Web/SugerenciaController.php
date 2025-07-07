<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ISugerenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SugerenciaController extends Controller
{
    private ISugerenciaService $sugerenciaService;

    public function __construct(ISugerenciaService $sugerenciaService)
    {
        $this->sugerenciaService = $sugerenciaService;
    }

    // Listar sugerencias (solo superadmin)
    public function index()
    {
        $sugerencias = $this->sugerenciaService->obtenerTodos();
        return view('super-admin.sugerencias.index', compact('sugerencias'));
    }

    // Mostrar formulario para crear sugerencia (cualquier autenticado)
    public function create()
    {
        return view('sugerencias.create');
    }

    // Guardar sugerencia (cualquier autenticado)
    public function store(Request $request)
    {
        $request->validate([
            'sugerencia' => ['required', 'string', 'max:2000', function($attribute, $value, $fail) {
                if (str_word_count($value) > 300) {
                    $fail('La sugerencia no puede superar las 300 palabras.');
                }
            }],
        ]);
        $this->sugerenciaService->crear([
            'usuario_id' => Auth::id(),
            'sugerencia' => $request->input('sugerencia'),
            'estado' => 'pendiente',
        ]);
        return redirect()->route('sugerencias.create')->with('success', '¡Sugerencia enviada!');
    }

    // Actualizar sugerencia (solo superadmin, por ejemplo para cambiar estado)
    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string',
        ]);
        $this->sugerenciaService->actualizar($id, [
            'estado' => $request->input('estado'),
        ]);
        return response()->json(['success' => true]);
    }

    // Eliminar sugerencia (solo superadmin)
    public function destroy($id)
    {
        $this->sugerenciaService->eliminar($id);
        return response()->json(['success' => true]);
    }

    // Mostrar detalle de sugerencia (opcional)
    public function show($id)
    {
        $sugerencia = $this->sugerenciaService->obtenerPorId($id);
        return response()->json(['sugerencia' => $sugerencia]);
    }

    // Historial de sugerencias del usuario autenticado
    public function historial()
    {
        $sugerencias = $this->sugerenciaService->obtenerPorUsuarioId(Auth::id());
        return view('sugerencias.historial', compact('sugerencias'));
    }
} 