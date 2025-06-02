<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IEstadoMesaService;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\ISucursalService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GerenteMesaController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IMesaService       $mesaService,
        private readonly IEstadoMesaService $estadoMesaService,
        private readonly ISucursalService   $sucursalService,
    )
    {
    }

    public function index(): View
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $sucursales = $this->sucursalService->obtenerPorUsuarioId($usuarioId);

        $mesas = $this->mesaService->obtenerTodos()
            ->whereIn('sucursal_id', $sucursales->pluck('id'))
            ->load(['estadoMesa', 'sucursal']);

        \Log::info('mesas: ' . $mesas);
        $estadosMesa = $this->estadoMesaService->obtenerActivos();

        $totalMesas = count($mesas);
        $totalAsientos = $mesas->sum('capacidad');
        $mesasOcupadas = $mesas->where('estadoMesa.nombre', 'Ocupada')->count();
        \Log::info('mesas ocupadas: ' . $mesasOcupadas);
        $ocupacion = $totalMesas > 0 ? ($mesasOcupadas / $totalMesas) * 100 : 0;

        \Log::info('mesas: ' . $mesas);
        return view('gerente-sucursal.mesas', compact(
            'mesas',
            'sucursales',
            'estadosMesa',
            'totalMesas',
            'totalAsientos',
            'ocupacion'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
                'capacidad' => 'required|integer|min:1|max:20',
                'sucursal_id' => 'required|exists:sucursales,id',
                'estado_mesa_id' => 'required|exists:estados_mesas,id'
            ]);

            $mesa = $this->mesaService->crear($request->all());

            if (!$mesa) {
                return response()->json(['error' => 'Error al crear la mesa'], 500);
            }

            return response()->json([
                'message' => 'Mesa creada exitosamente',
                'mesa' => $mesa
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al crear mesa: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al crear la mesa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $mesa = $this->mesaService->obtenerPorId($id);
            if (!$mesa) {
                return response()->json(['error' => 'Mesa no encontrada'], 404);
            }

            // Cargar las relaciones necesarias
            $mesa->load(['estadoMesa', 'sucursal']);

            return response()->json(['mesa' => $mesa]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener mesa: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener la mesa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
                'capacidad' => 'required|integer|min:1|max:20',
                'sucursal_id' => 'required|exists:sucursales,id',
                'estado_mesa_id' => 'required|exists:estados_mesas,id'
            ]);

            $success = $this->mesaService->actualizar($id, $request->all());

            if (!$success) {
                return response()->json(['error' => 'Error al actualizar la mesa'], 500);
            }

            return response()->json(['message' => 'Mesa actualizada exitosamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar mesa: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al actualizar la mesa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $success = $this->mesaService->eliminar($id);

            if (!$success) {
                return response()->json(['error' => 'Error al eliminar la mesa'], 500);
            }

            return response()->json(['message' => 'Mesa eliminada exitosamente']);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar mesa: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al eliminar la mesa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstado(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'estado_mesa_id' => 'required|exists:estados_mesas,id'
            ]);

            $success = $this->mesaService->cambiarEstadoMesa($id, $request->input('estado_mesa_id'));

            if (!$success) {
                return response()->json(['error' => 'Error al cambiar el estado de la mesa'], 500);
            }

            return response()->json(['message' => 'Estado de la mesa actualizado exitosamente']);
        } catch (\Exception $e) {
            \Log::error('Error al cambiar estado de mesa: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cambiar el estado de la mesa: ' . $e->getMessage()
            ], 500);
        }
    }
}
