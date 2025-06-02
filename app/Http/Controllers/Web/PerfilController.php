<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Restaurante;
use App\Models\Sucursal;
use App\Models\Usuario;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class PerfilController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IUsuarioService            $usuarioService,
        private readonly ISucursalService           $sucursalService,
        private readonly IAsignacionPersonalService $asignacionPersonalService
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
        $sucursal = null;
        $restaurante = null;

        // Obtener asignación del usuario
        $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId($usuario->id);

        if ($asignacion) {
            $sucursal = $asignacion->sucursal;
            $restaurante = $sucursal->restaurante;
        }


        return view('perfil', compact('usuario', 'sucursal', 'restaurante', 'asignacion'));
    }
}
