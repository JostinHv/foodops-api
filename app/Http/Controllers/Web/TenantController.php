<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IImagenService;
use App\Services\Interfaces\IPlanSuscripcionService;
use App\Services\Interfaces\IRolService;
use App\Services\Interfaces\ITenantService;
use App\Services\Interfaces\IUsuarioRolService;
use App\Services\Interfaces\IUsuarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(
        private readonly ITenantService          $tenantService,
        private readonly IImagenService          $imagenService,
        private readonly IPlanSuscripcionService $planSuscripcionService,
        private readonly IUsuarioService         $usuarioService,
        private readonly IRolService             $rolService,
        private readonly IUsuarioRolService      $usuarioRolService
    )
    {
    }

    public function index(): View
    {
        $tenants = $this->tenantService->obtenerTodos();
        $planes = $this->planSuscripcionService->obtenerActivos();
        return view('super-admin.tenants.index', compact('tenants', 'planes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dominio' => 'required|string|unique:tenants,dominio|max:255',
            'datos_contacto.nombre_empresa' => 'required|string|max:255',
            'datos_contacto.email' => 'required|email|max:255',
            'datos_contacto.telefono' => 'nullable|string|max:20',
            'datos_contacto.direccion' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'activo' => 'boolean'
        ]);

        $datos = $request->except('logo');

//        // Procesar y guardar el logo si se proporcionó
//        if ($request->hasFile('logo')) {
//            $imagen = $this->imagenService->guardarImagen($request->file('logo'), 'tenants/logos');
//            if ($imagen) {
//                $datos['logo_id'] = $imagen->id;
//            }
//        }

        $this->tenantService->crear($datos);

        return redirect()->route('superadmin.tenant')
            ->with('success', 'Tenant creado exitosamente');
    }

    public function show(int $id): View
    {
        $tenant = $this->tenantService->obtenerPorId($id);
        if (!$tenant) {
            abort(404, 'Tenant no encontrado');
        }

        $usuarios = $this->usuarioService->obtenerPorTenantId($id);
        $roles = $this->rolService->obtenerActivos();

        return view('super-admin.tenants.show', compact('tenant', 'usuarios', 'roles'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'dominio' => 'required|string|max:255|unique:tenants,dominio,' . $id,
            'datos_contacto.nombre_empresa' => 'required|string|max:255',
            'datos_contacto.email' => 'required|email|max:255',
            'datos_contacto.telefono' => 'nullable|string|max:20',
            'datos_contacto.direccion' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'activo' => 'boolean'
        ]);

        $datos = $request->except('logo');

        if ($request->hasFile('logo')) {
            $imagen = $this->imagenService->guardarImagen($request->file('logo'), 'tenants/logos');
            if ($imagen) {
                $datos['logo_id'] = $imagen->id;

                // Eliminar logo anterior si existe
                $tenant = $this->tenantService->obtenerPorId($id);
                if ($tenant && $tenant->logo_id) {
                    $this->imagenService->eliminar($tenant->logo_id);
                }
            }
        }

        $this->tenantService->actualizar($id, $datos);

        return redirect()->route('superadmin.tenant')
            ->with('success', 'Tenant actualizado exitosamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        $tenant = $this->tenantService->obtenerPorId($id);
        if ($tenant && $tenant->logo_id) {
            $this->imagenService->eliminar($tenant->logo_id);
        }

        $this->tenantService->eliminar($id);

        return redirect()->route('superadmin.tenant')
            ->with('success', 'Tenant eliminado exitosamente');
    }

    public function toggleActivo(int $id): RedirectResponse
    {
        $tenant = $this->tenantService->obtenerPorId($id);
        if (!$tenant) {
            return redirect()->route('superadmin.tenant')
                ->with('error', 'Tenant no encontrado');
        }

        $this->tenantService->actualizar($id, ['activo' => !$tenant->activo]);

        $mensaje = $tenant->activo ? 'desactivado' : 'activado';
        return redirect()->route('superadmin.tenant')
            ->with('success', "Tenant {$mensaje} exitosamente");
    }

    public function agregarUsuario(Request $request, int $tenantId): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'celular' => 'nullable|string|max:15',
            'rol_id' => 'required|exists:roles,id',
            'password' => 'required|string',
        ]);

        $usuario = $this->usuarioService->crear([
            'tenant_id' => $tenantId,
            'email' => $request->email,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'nro_celular' => $request->celular,
            'password' => Hash::make($request->password),
            'activo' => true
        ]);

        if ($usuario) {
            $this->usuarioRolService->crear([
                'usuario_id' => $usuario->id,
                'rol_id' => $request->rol_id
            ]);
        }

        return redirect()->route('superadmin.tenant.show', $tenantId)
            ->with('success', 'Usuario agregado exitosamente');
    }

    public function desactivarUsuario(int $tenantId, int $usuarioId): RedirectResponse
    {
        $usuario = $this->usuarioService->obtenerPorId($usuarioId);

        if (!$usuario || $usuario->tenant_id !== $tenantId) {
            return redirect()->route('superadmin.tenant.show', $tenantId)
                ->with('error', 'Usuario no encontrado');
        }

        $this->usuarioService->cambiarEstadoAutomatico($usuarioId);

        return redirect()->route('superadmin.tenant.show', $tenantId)
            ->with('success', 'Usuario eliminado exitosamente');
    }

    public function cambiarRolUsuario(Request $request, int $tenantId, int $usuarioId): RedirectResponse
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id'
        ]);

        $usuario = $this->usuarioService->obtenerPorId($usuarioId);

        if (!$usuario || $usuario->tenant_id !== $tenantId) {
            return redirect()->route('superadmin.tenant.show', $tenantId)
                ->with('error', 'Usuario no encontrado');
        }

        $this->usuarioRolService->actualizarRolUsuario($usuarioId, $request->rol_id);

        return redirect()->route('superadmin.tenant.show', $tenantId)
            ->with('success', 'Rol de usuario actualizado exitosamente');
    }
}
