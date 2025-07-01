<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\IRestauranteService;
use App\Services\Interfaces\IRolService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioRolService;
use App\Services\Interfaces\IUsuarioService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly IUsuarioService            $usuarioService,
        private readonly IRolService                $rolService,
        private readonly IRestauranteService        $restauranteService,
        private readonly ISucursalService           $sucursalService,
        private readonly IUsuarioRolService         $usuarioRolService,
        private readonly IAsignacionPersonalService $asignacionPersonalService,
    )
    {
    }

    public function index(): View|Application|Factory
    {
        $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
        $usuario = $this->usuarioService->obtenerPorId($usuarioId);

        // Obtener datos para la vista
        $usuarios = $this->usuarioService->obtenerUsuariosOperativosPorTenantId($usuario->tenant_id)->whereNotIn('id', [$usuarioId]);
        $roles = $this->rolService->obtenerRolesActivosPorId([3, 4, 5, 6]);
        $sucursales = $this->sucursalService->obtenerTodos();

        // Cargar relaciones
        if ($usuarios) {
            $usuarios->load(['roles', 'asignacionesPersonal.sucursal.restaurante']);
        }

        // Estadísticas para los badges
        $totalUsuarios = $usuarios->count();
        $usuariosActivos = $usuarios->where('activo', true)->count();
        $gerentes = $usuarios->filter(function ($usuario) {
            return $usuario->roles->contains('nombre', 'gerente');
        })->count();
        $personalOperativo = $usuarios->filter(function ($usuario) {
            return $usuario->roles->contains('nombre', 'mesero') ||
                $usuario->roles->contains('nombre', 'cajero') ||
                $usuario->roles->contains('nombre', 'cocinero');
        })->count();

        return view('admin-tenant.usuarios', compact(
            'usuarios',
            'roles',
            'sucursales',
            'totalUsuarios',
            'usuariosActivos',
            'gerentes',
            'personalOperativo'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $usuarioId = $this->getCurrentUser()->getAuthIdentifier();
            $usuario = $this->usuarioService->obtenerPorId($usuarioId);

            $request->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:usuarios,email',
                'nro_celular' => 'nullable|string|max:20',
                'rol_id' => 'required|exists:roles,id',
                'password' => 'required|string|confirmed',
                'sucursal_id' => 'nullable|exists:sucursales,id',
                'notas_asignacion' => 'nullable|string|max:255',
            ]);

            $data = $request->all();
            $data['tenant_id'] = $usuario->tenant_id;
            $data['password'] = Hash::make($data['password']);
            $data['activo'] = true;

            $data['nro_celular'] = $data['celular'] ?? null; // Asegurar compatibilidad con el campo celular
            unset($data['celular']);
            $usuario = $this->usuarioService->crear($data);

            // Asignar rol al usuario
            $usuarioRolData = [
                'usuario_id' => $usuario->id,
                'rol_id' => $data['rol_id'],
            ];
            $this->usuarioRolService->crear($usuarioRolData);

            // Si se proporcionó una sucursal, crear la asignación
            if (!empty($data['sucursal_id'])) {
                $sucursal = $this->sucursalService->obtenerPorId($data['sucursal_id']);
                $this->sucursalService->actualizar($sucursal->id, [
                    'usuario_id' => $usuario->id,
                ]);
                $this->usuarioService->actualizar($usuario->id, [
                    'restaurante_id' => $sucursal->restaurante_id,
                ]);
                if ($sucursal) {
                    // Crear la asignación de personal
                    $this->asignacionPersonalService->crear([
                        'tenant_id' => $data['tenant_id'],
                        'usuario_id' => $usuario->id,
                        'sucursal_id' => $data['sucursal_id'],
                        'tipo' => $this->obtenerTipoAsignacionPorRol($data['rol_id']),
                        'notas' => $data['notas_asignacion'] ?? null,
                        'fecha_asignacion' => now(),
                        'activo' => true
                    ]);
                }
            }

            return redirect()->route('tenant.usuarios')
                ->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el usuario: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        try {
            // Preparar las reglas de validación
            $rules = [
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'celular' => 'nullable|string|max:20',
                'rol_id' => 'required|exists:roles,id',
                'sucursal_id' => 'nullable|exists:sucursales,id',
                'notas_asignacion' => 'nullable|string|max:500',
                'password' => 'nullable|min:1|confirmed'
            ];

            // Si el email es diferente al actual, agregar regla de validación
            if ($request->email !== $usuario->email) {
                $rules['email'] = 'required|email|unique:usuarios,email';
            }

            // Validar request
            $request->validate($rules);

            // Preparar datos a actualizar
            $data = $request->all();

            $data['nro_celular'] = $data['celular'] ?? null; // Asegurar compatibilidad con el campo celular
            unset($data['celular']);

            // Si el email no cambió, eliminarlo del array de datos
            if ($request->email === $usuario->email) {
                unset($data['email']);
            }

            // Si no hay nueva contraseña, eliminarla del array
            if (empty($data['password'])) {
                unset($data['password']);
            }


            // Solo actualizar la contraseña si se proporciona una nueva
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }


            // Actualizar rol del usuario
            if (!$this->usuarioRolService->actualizarRolUsuario($usuario->id, $data['rol_id'])) {
                $this->usuarioRolService->crear([
                    'usuario_id' => $usuario->id,
                    'rol_id' => $data['rol_id'],
                ]);
            }

            // Manejar la asignación de sucursal
            if (!empty($data['sucursal_id'])) {
                $sucursal = $this->sucursalService->obtenerPorId($data['sucursal_id']);
                $data['restaurante_id'] = $sucursal->restaurante_id;
                $this->sucursalService->actualizar($sucursal->id, [
                    'usuario_id' => $usuario->id,
                ]);
                $this->usuarioService->actualizar($usuario->id, $data);

                if ($sucursal) {
                    // Buscar asignación existente
                    $asignacionExistente = $this->asignacionPersonalService->obtenerPorUsuarioId($usuario->id);

                    if ($asignacionExistente) {
                        // Actualizar asignación existente
                        $this->asignacionPersonalService->actualizar($asignacionExistente->id, [
                            'sucursal_id' => $data['sucursal_id'],
                            'tipo' => $this->obtenerTipoAsignacionPorRol($data['rol_id']),
                            'notas' => $data['notas_asignacion'] ?? null,
                            'activo' => true
                        ]);
                    } else {
                        // Crear nueva asignación
                        $this->asignacionPersonalService->crear([
                            'tenant_id' => $usuario->tenant_id,
                            'usuario_id' => $usuario->id,
                            'sucursal_id' => $data['sucursal_id'],
                            'tipo' => $this->obtenerTipoAsignacionPorRol($data['rol_id']),
                            'notas' => $data['notas_asignacion'] ?? null,
                            'fecha_asignacion' => now(),
                            'activo' => true
                        ]);
                    }
                }
            }

            return redirect()->route('tenant.usuarios')
                ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el usuario: ' . $exception->getMessage()])
                ->withInput();
        }
    }

    private function obtenerTipoAsignacionPorRol(int $rolId): string
    {
        $rol = $this->rolService->obtenerPorId($rolId);
        return match ($rol->nombre) {
            'mesero' => 'mesero',
            'cajero' => 'cajero',
            'cocinero' => 'cocinero',
            'gerente' => 'gerente',
            default => 'otro'
        };
    }

    public function show(Usuario $usuario): \Illuminate\Http\JsonResponse
    {
        try {
            $usuario->load(['roles', 'restaurante', 'asignacionesPersonal', 'asignacionesPersonal.sucursal']);
            return response()->json(['usuario' => $usuario]);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Error al obtener los detalles del usuario'], 500);
        }
    }

    public function toggleActivo(Usuario $usuario): RedirectResponse
    {
        try {
            $resultado = $this->usuarioService->cambiarEstadoAutomatico($usuario->id);
            if (!$resultado) {
                return redirect()->back()
                    ->withErrors(['error' => 'No se pudo cambiar el estado del usuario']);
            }
            return redirect()->route('tenant.usuarios')
                ->with('success', 'Estado del usuario actualizado exitosamente');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al cambiar el estado del usuario']);
        }
    }
}
