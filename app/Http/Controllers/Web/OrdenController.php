<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IItemMenuService;
use App\Services\Interfaces\IItemOrdenService;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\IOrdenService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrdenController extends Controller
{
    use AuthenticatedUserTrait;

    private IOrdenService $ordenService;
    private IItemMenuService $itemMenuService;
    private IMesaService $mesaService;
    private IItemOrdenService $itemOrdenService;

    public function __construct(
        IOrdenService     $ordenService,
        IItemMenuService  $itemMenuService,
        IMesaService      $mesaService,
        IItemOrdenService $itemOrdenService,
    )
    {
        $this->ordenService = $ordenService;
        $this->itemMenuService = $itemMenuService;
        $this->mesaService = $mesaService;
        $this->itemOrdenService = $itemOrdenService;
    }

    /**
     * Muestra la lista de órdenes
     */
    public function index(): View
    {
        $ordenes = $this->ordenService->obtenerTodos();
        return view('mesero.orden', compact('ordenes'));
    }

    /**
     * Muestra el formulario para crear una nueva orden
     */
    public function create(): View
    {
        $mesas = $this->mesaService->obtenerMesasDisponibles();
        $productos = $this->itemMenuService->obtenerTodosItemsDisponibles();
        return view('mesero.nueva-orden', compact('mesas', 'productos'));
    }

    /**
     * Muestra una orden específica
     */
    public function show(int $id): View
    {
        $orden = $this->ordenService->obtenerPorId($id);
        return view('mesero.orden-detalle', compact('orden'));
    }

    public function store(Request $request): RedirectResponse
    {
        \Log::log('info', 'Iniciando el proceso de creación de orden' . json_encode($request->all()));
        try {
            if (!$this->validateAuthenticatedUser()) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Debe iniciar sesión para realizar esta acción');
            }

            $usuario = $this->getCurrentUser();

            $request->validate([
                'cliente' => 'required|string|max:255',
                'mesa_id' => 'required|exists:mesas,id',
                'productos' => 'required|array',
                'productos.*.producto_id' => 'required|exists:items_menus,id',
                'productos.*.cantidad' => 'required|integer|min:1',
            ]);

            $ordenData = [
                'mesa_id' => $request->input('mesa_id'),
                'mesero_id' => $usuario->id,
                'nro_orden' => $this->ordenService->generarNumeroOrden(),
                'nombre_cliente' => $request->input('cliente'),
                'tipo_servicio' => 'mesa',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            \DB::beginTransaction();

            $orden = $this->ordenService->crear($ordenData);

            $itemsOrden = collect($request->input('productos'))->map(function ($producto) use ($orden) {
                $itemMenu = $this->itemMenuService->obtenerPorId($producto['producto_id']);
                return [
                    'orden_id' => $orden->id,
                    'item_menu_id' => $itemMenu->id,
                    'cantidad' => $producto['cantidad'],
                    'monto' => $itemMenu->precio * $producto['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();

            $this->itemOrdenService->crearItemsOrden($itemsOrden);

            \DB::commit();

            \Log::info('Orden creada exitosamente', [
                'orden_id' => $orden->id,
                'mesero_id' => $usuario->id,
                'items' => count($itemsOrden)
            ]);

            return redirect()
                ->route('orden-index')
                ->with('success', 'Orden creada con éxito');

        } catch (ValidationException $e) {
            \Log::warning('Error de validación al crear orden', [
                'errors' => $e->errors()
            ]);
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Error al crear orden', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error al crear la orden. Por favor, inténtelo de nuevo.')
                ->withInput();
        }
    }
}
