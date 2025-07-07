<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\ICajaService;
use App\Services\Interfaces\IMovimientoCajaService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IAsignacionPersonalService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CajeroController extends Controller
{
    public function __construct(
        private readonly ICajaService           $cajaService,
        private readonly IMovimientoCajaService $movimientoCajaService,
        private readonly IAsignacionPersonalService $asignacionPersonalService
    ) {}

    public function index(): View
    {
        return view('cajero.facturacion'); 
    }

    public function caja(): View|RedirectResponse
    {
        // Obtener la sucursal del usuario cajero
        $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
        if (!$asignacion) {
            return redirect()->route('home')->with('error', 'No tienes una sucursal asignada.');
        }

        $sucursalId = $asignacion->sucursal_id;
        $cajaAbierta = $this->cajaService->obtenerAbiertaPorSucursal($sucursalId);

        $caja = null;
        $movimientos = collect();

        if ($cajaAbierta) {
            $caja = $cajaAbierta;
            $movimientos = $this->cajaService->obtenerMovimientosRecientes($caja->id, 10);
        }

        return view('cajero.caja', compact('cajaAbierta', 'caja', 'movimientos'));
    }

    public function aperturaCaja(): View|RedirectResponse
    {
        // Verificar que no haya una caja abierta
        $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
        if (!$asignacion) {
            return redirect()->route('home')->with('error', 'No tienes una sucursal asignada.');
        }

        $cajaAbierta = $this->cajaService->obtenerAbiertaPorSucursal($asignacion->sucursal_id);
        if ($cajaAbierta) {
            return redirect()->route('cajero.caja')->with('error', 'Ya existe una caja abierta.');
        }

        return view('cajero.apertura_caja');
    }

    public function storeAperturaCaja(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'monto_inicial.required' => 'El monto inicial es obligatorio.',
            'monto_inicial.numeric' => 'El monto inicial debe ser un número.',
            'monto_inicial.min' => 'El monto inicial no puede ser negativo.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Obtener la sucursal del usuario
            $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
            if (!$asignacion) {
                return redirect()->route('home')->with('error', 'No tienes una sucursal asignada.');
            }

            $datos = [
                'sucursal_id' => $asignacion->sucursal_id,
                'monto_inicial' => $request->monto_inicial,
                'observaciones' => $request->observaciones,
            ];

            $this->cajaService->abrirCaja($datos);

            return redirect()->route('cajero.caja')
                ->with('success', 'Caja abierta correctamente con un monto inicial de S/. ' . number_format($request->monto_inicial, 2));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al abrir la caja: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cierreCaja(): View|RedirectResponse
    {
        // Verificar que haya una caja abierta
        $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
        if (!$asignacion) {
            return redirect()->route('home')->with('error', 'No tienes una sucursal asignada.');
        }

        $cajaAbierta = $this->cajaService->obtenerAbiertaPorSucursal($asignacion->sucursal_id);
        if (!$cajaAbierta) {
            return redirect()->route('cajero.caja')->with('error', 'No hay una caja abierta para cerrar.');
        }

        // Calcular montos esperados para mostrar en la vista
        $montoFinalEsperado = $this->cajaService->calcularMontoFinalEsperado($cajaAbierta->id);

        return view('cajero.cierre_caja', compact('cajaAbierta', 'montoFinalEsperado'));
    }

    public function storeCierreCaja(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'monto_efectivo_contado' => 'required|numeric|min:0',
            'monto_tarjetas' => 'nullable|numeric|min:0',
            'monto_transferencias' => 'nullable|numeric|min:0',
            'monto_otros' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'monto_efectivo_contado.required' => 'El monto de efectivo contado es obligatorio.',
            'monto_efectivo_contado.numeric' => 'El monto de efectivo debe ser un número.',
            'monto_efectivo_contado.min' => 'El monto de efectivo no puede ser negativo.',
            'monto_tarjetas.numeric' => 'El monto de tarjetas debe ser un número.',
            'monto_tarjetas.min' => 'El monto de tarjetas no puede ser negativo.',
            'monto_transferencias.numeric' => 'El monto de transferencias debe ser un número.',
            'monto_transferencias.min' => 'El monto de transferencias no puede ser negativo.',
            'monto_otros.numeric' => 'El monto de otros debe ser un número.',
            'monto_otros.min' => 'El monto de otros no puede ser negativo.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Obtener la caja abierta
            $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
            if (!$asignacion) {
                return redirect()->route('home')->with('error', 'No tienes una sucursal asignada.');
            }

            $cajaAbierta = $this->cajaService->obtenerAbiertaPorSucursal($asignacion->sucursal_id);
            if (!$cajaAbierta) {
                return redirect()->route('cajero.caja')->with('error', 'No hay una caja abierta para cerrar.');
            }

            $datos = [
                'monto_efectivo_contado' => $request->monto_efectivo_contado,
                'monto_tarjetas' => $request->monto_tarjetas ?? 0,
                'monto_transferencias' => $request->monto_transferencias ?? 0,
                'monto_otros' => $request->monto_otros ?? 0,
                'observaciones' => $request->observaciones,
            ];

            $this->cajaService->cerrarCaja($cajaAbierta->id, $datos);

            $totalContado = $request->monto_efectivo_contado + ($request->monto_tarjetas ?? 0) + ($request->monto_transferencias ?? 0) + ($request->monto_otros ?? 0);

            return redirect()->route('cajero.caja')
                ->with('success', 'Caja cerrada correctamente. Total contado: S/. ' . number_format($totalContado, 2));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cerrar la caja: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function movimientosCaja(): View|RedirectResponse
    {
        // Obtener la sucursal del usuario cajero
        $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
        if (!$asignacion) {
            return redirect()->route('home')->with('error', 'No tienes una sucursal asignada.');
        }

        $sucursalId = $asignacion->sucursal_id;
        $cajaAbierta = $this->cajaService->obtenerAbiertaPorSucursal($sucursalId);

        $movimientos = collect();

        if ($cajaAbierta) {
            $movimientos = $this->movimientoCajaService->obtenerPorCaja($cajaAbierta->id);
        }

        return view('cajero.movimientos_caja', compact('movimientos'));
    }
}
