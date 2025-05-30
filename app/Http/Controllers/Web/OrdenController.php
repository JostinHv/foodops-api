<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrearOrdenRequest;
use App\Services\Interfaces\IItemMenuService;
use App\Services\Interfaces\IItemOrdenService;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\IOrdenService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function store(CrearOrdenRequest $request): RedirectResponse
    {
        try {
            if (!$this->validateAuthenticatedUser()) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Debe iniciar sesión para realizar esta acción');
            }

            $orden = $this->ordenService->crearOrden(
                $request->validated(),
                $this->getCurrentUser()->getAuthIdentifier()
            );

            return redirect()
                ->route('orden-index')
                ->with('success', 'Orden creada con éxito');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear la orden. Por favor, inténtelo de nuevo.')
                ->withInput();
        }
    }
}
