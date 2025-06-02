<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IImagenService;
use App\Services\Interfaces\IMetodoPagoService;
use App\Services\Interfaces\IPlanSuscripcionService;
use App\Services\Interfaces\IRestauranteService;
use App\Services\Interfaces\IRolService;
use App\Services\Interfaces\ITenantService;
use App\Services\Interfaces\ITenantSuscripcionService;
use App\Services\Interfaces\IUsuarioRolService;
use App\Services\Interfaces\IUsuarioService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function __construct(
        private readonly ITenantService            $tenantService,
        private readonly IImagenService            $imagenService,
        private readonly IPlanSuscripcionService   $planSuscripcionService,
        private readonly IUsuarioService           $usuarioService,
        private readonly IRolService               $rolService,
        private readonly IUsuarioRolService        $usuarioRolService,
        private readonly IRestauranteService       $restauranteService,
        private readonly IMetodoPagoService        $metodoPagoService,
        private readonly ITenantSuscripcionService $tenantSuscripcionService
    )
    {
    }

    public function index(): View
    {
        $tenants = $this->tenantService->obtenerTodos();
        $planes = $this->planSuscripcionService->obtenerActivos();
        $metodosPago = $this->metodoPagoService->obtenerActivos();

        // Obtener contadores para cada tenant
        $tenants->each(function ($tenant) {
            // Contar usuarios activos del tenant
            $usuarios = $this->usuarioService->obtenerPorTenantId($tenant->id);
            $tenant->usuarios_count = $usuarios->where('activo', true)->count();

            // Contar restaurantes del tenant
            $restaurantes = $this->restauranteService->obtenerPorTenantId($tenant->id);
            $tenant->restaurantes_count = $restaurantes->count();
        });

        return view('super-admin.tenants.index', compact('tenants', 'planes', 'metodosPago'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'dominio' => 'required|string|unique:tenants,dominio|max:255',
                'datos_contacto.nombre_empresa' => 'required|string|max:255',
                'datos_contacto.email' => 'required|email|max:255',
                'datos_contacto.telefono' => 'nullable|string|max:20',
                'datos_contacto.direccion' => 'nullable|string|max:255',
                'logo' => 'nullable|image|max:2048',
                'activo' => 'boolean',
                'plan_suscripcion_id' => 'required|exists:planes_suscripciones,id',
                'metodo_pago_id' => 'required|exists:metodos_pagos,id',
                'fecha_inicio' => 'required|date',
                'renovacion_automatica' => 'boolean'
            ]);

            // Separar los datos del tenant y la suscripción
            $datosTenant = $request->except([
                'plan_suscripcion_id',
                'metodo_pago_id',
                'fecha_inicio',
                'renovacion_automatica'
            ]);

            DB::beginTransaction();
            // Procesar y guardar el logo si se proporcionó
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoPath = $logo->store('tenants/logos', 'public');
                $imagen = $this->imagenService->crear([
                    'url' => $logoPath,
                    'activo' => true,
                ]);
                if ($imagen) {
                    $datosTenant['logo_id'] = $imagen->id;
                }
            }

            // Crear el tenant
            $tenant = $this->tenantService->crear($datosTenant);

            if ($tenant) {
                // Obtener el plan seleccionado
                $plan = $this->planSuscripcionService->obtenerPorId($request->plan_suscripcion_id);

                // Calcular la fecha de fin basada en el intervalo del plan
                $fechaFin = null;
                if ($plan) {
                    $fechaInicio = Carbon::parse($request->fecha_inicio);
                    switch ($plan->intervalo) {
                        case 'mes':
                            $fechaFin = $fechaInicio->copy()->addMonth();
                            break;
                        case 'anual':
                            $fechaFin = $fechaInicio->copy()->addYear();
                            break;
                    }
                }
                \Log::info('tenant_id: ' . $tenant->id);
                // Crear la suscripción
                $this->tenantSuscripcionService->crear([
                    'tenant_id' => $tenant->id,
                    'plan_suscripcion_id' => $request->plan_suscripcion_id,
                    'metodo_pago_id' => $request->metodo_pago_id,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $fechaFin,
                    'estado' => 'activo',
                    'precio_acordado' => $plan ? $plan->precio : null,
                    'renovacion_automatica' => $request->boolean('renovacion_automatica'),
                    'notas' => 'Suscripción inicial'
                ]);
            }
            DB::commit();
            return redirect()->route('superadmin.tenant')
                ->with('success', 'Tenant creado exitosamente');
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error($exception->getTraceAsString());
            \Log::error($exception->getMessage());
            return redirect()->route('superadmin.tenant')
                ->with('error', 'Error al crear el tenant: ' . $exception->getMessage());
        }
    }

    public function show(int $id): View
    {
        $tenant = $this->tenantService->obtenerPorId($id);
        if (!$tenant) {
            abort(404, 'Tenant no encontrado');
        }

        $usuarios = $this->usuarioService->obtenerPorTenantId($id);
        $roles = $this->rolService->obtenerRolesActivosPorId([2, 3, 4, 5, 6]); // Asumiendo que los IDs de roles son 1, 2 y 3
        $planes = $this->planSuscripcionService->obtenerActivos();

        return view('super-admin.tenants.show', compact('tenant', 'usuarios', 'roles', 'planes'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'dominio' => 'required|string|max:255|unique:tenants,dominio,' . $id,
            'datos_contacto.nombre_empresa' => 'required|string|max:255',
            'datos_contacto.email' => 'required|email|max:255',
            'datos_contacto.telefono' => 'nullable|string|max:20',
            'datos_contacto.direccion' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
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

    public function checkDomain(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'dominio' => 'required|string|max:255',
            'id' => 'nullable|integer|exists:tenants,id'
        ]);

        $query = Tenant::where('dominio', $request->dominio);
        
        // Si se está editando un tenant existente, excluirlo de la validación
        if ($request->has('id')) {
            $query->where('id', '!=', $request->id);
        }

        $exists = $query->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Este dominio ya está en uso' : 'Dominio disponible'
        ]);
    }
}
