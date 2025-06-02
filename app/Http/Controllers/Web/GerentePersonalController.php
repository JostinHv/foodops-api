<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\IRolService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioRolService;
use App\Services\Interfaces\IUsuarioService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GerentePersonalController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IUsuarioService            $usuarioService,
        private readonly IAsignacionPersonalService $asignacionPersonalService,
        private readonly ISucursalService           $sucursalService,
        private readonly IRolService                $rolService,
        private readonly IUsuarioRolService         $usuarioRolService,
    )
    {
    }

    public function index(): View
    {
        $usuarioActual = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
        $tenantId = $usuarioActual->tenant_id;
        $sucursales = $this->sucursalService->obtenerPorUsuarioId($usuarioActual->id);
        $sucursalIds = $sucursales->pluck('id')->toArray();

        $asignaciones = $this->asignacionPersonalService->obtenerPorTenantId($tenantId)->whereIn('sucursal_id', $sucursalIds);
        $usuarios = $asignaciones->pluck('usuario')->filter()->whereNotIn('id', [$this->getCurrentUser()->getAuthIdentifier()]);;

        $roles = [
            'mesero' => 'Mesero',
            'cocinero' => 'Cocinero',
            'gerente' => 'Gerente',
            'cajero' => 'Cajero',
        ];

        return view('gerente-sucursal.personal', compact('usuarios', 'sucursales', 'asignaciones', 'roles'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => 'required|email|unique:usuarios,email',
                'celular' => 'required|string|max:20',
                'sucursal_id' => 'required|exists:sucursales,id',
                'tipo' => 'required|string|in:mesero,cocinero,cajero,gerente',
                'password' => 'required|string|confirmed'
            ]);
            $usuarioActual = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());

            $usuario = $this->usuarioService->crear([
                'tenant_id' => $usuarioActual->tenant_id,
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'email' => $validated['email'],
                'nro_celular' => $validated['celular'],
                'password' => Hash::make($validated['password']),
                'activo' => true
            ]);

            $rol = $this->rolService->obtenerTodos()
                ->where('nombre', strtolower($validated['tipo']))
                ->first();

            $this->usuarioRolService->crear([
                'usuario_id' => $usuario->id,
                'rol_id' => $rol->id,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->asignacionPersonalService->asignarUsuarioSucursal($usuario, $validated['sucursal_id']);

            return response()->json([
                'message' => 'Personal creado exitosamente',
                'usuario' => $usuario
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear personal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear el personal'
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->obtenerPorId($id);
            $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId($id);

            if (!$usuario) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'usuario' => $usuario,
                'asignacion' => $asignacion
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener datos del personal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener los datos del personal'
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'celular' => 'required|string|max:20',
                'sucursal_id' => 'required|exists:sucursales,id',
                'tipo' => 'required|string|in:mesero,cocinero,cajero,gerente',
                'password' => 'nullable|string|confirmed'
            ]);

            $datosActualizacion = [
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'celular' => $validated['celular']
            ];

            // Solo actualizar la contraseña si se proporciona una nueva
            if (!empty($validated['password'])) {
                $datosActualizacion['password'] = Hash::make($validated['password']);
            }

            $this->usuarioService->actualizar($id, $datosActualizacion);

            $usuario = $this->usuarioService->obtenerPorId($id);

            // Buscar y actualizar el rol correspondiente
            $rol = $this->rolService->obtenerTodos()
                ->where('nombre', strtolower($validated['tipo']))
                ->first();

            if ($rol) {
                $this->usuarioRolService->actualizarRolUsuario($id, $rol->id);
            }

            // Actualizar asignación de personal
            $this->asignacionPersonalService->asignarUsuarioSucursal($usuario, $validated['sucursal_id']);


            return response()->json([
                'message' => 'Personal actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar personal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar el personal'
            ], 500);
        }
    }

    public function toggleActivo(int $id): JsonResponse
    {
        try {
            $this->usuarioService->cambiarEstadoAutomatico($id);
            return response()->json([
                'message' => 'Estado del personal actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del personal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al cambiar el estado del personal'
            ], 500);
        }
    }

    public function checkEmail(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $exists = $this->usuarioService->obtenerTodos()
                ->where('email', $request->email)
                ->isNotEmpty();

            return response()->json([
                'exists' => $exists
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar email: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al verificar el email'
            ], 500);
        }
    }
}
