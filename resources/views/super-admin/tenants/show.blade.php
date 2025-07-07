@extends('layouts.app')

@section('title', 'Detalles del Tenant - Super Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/tenant.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ $tenant->datos_contacto['nombre_empresa'] ?? $tenant->dominio }}</h1>
                <p class="mb-0 text-muted">Gestión de usuarios y roles</p>
            </div>
            <div>
                <a href="{{ route('superadmin.tenant') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editarTenantModal" data-tenant-id="{{ $tenant->id }}"
                        data-tenant-dominio="{{ $tenant->dominio }}"
                        data-tenant-nombre="{{ $tenant->datos_contacto['nombre_empresa'] ?? '' }}"
                        data-tenant-email="{{ $tenant->datos_contacto['email'] ?? '' }}"
                        data-tenant-telefono="{{ $tenant->datos_contacto['telefono'] ?? '' }}"
                        data-tenant-direccion="{{ $tenant->datos_contacto['direccion'] ?? '' }}"
                        data-tenant-activo="{{ $tenant->activo ? '1' : '0' }}">
                    <i class="bi bi-pencil me-2"></i>Editar Tenant
                </button>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#agregarUsuarioModal">
                    <i class="bi bi-person-plus me-2"></i>Agregar Usuario
                </button>
            </div>
        </div>

        <!-- Información del Tenant -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información del Tenant</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 text-center mb-3">
                                @if($tenant->logo)
                                    <img src="{{ Storage::url($tenant->logo->url) }}"
                                         alt="Logo {{ $tenant->datos_contacto['nombre_empresa'] }}"
                                         class="img-thumbnail"
                                         style="max-height: 100px">
                                @else
                                    <div class="border rounded p-3 text-muted">
                                        <i class="bi bi-building fs-1"></i>
                                        <p class="small mb-0">Sin logo</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Dominio</label>
                                <p class="mb-0">{{ $tenant->dominio }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Estado</label>
                                <p class="mb-0">
                                <span class="badge {{ $tenant->activo ? 'bg-success' : 'bg-danger' }}">
                                    {{ $tenant->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0">{{ $tenant->datos_contacto['email'] ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-1">{{ $usuarios->count() }}</h3>
                                    <p class="text-muted mb-0">Usuarios Totales</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-1">{{ $usuarios->where('activo', true)->count() }}</h3>
                                    <p class="text-muted mb-0">Usuarios Activos</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <h6 class="mb-2">Distribución de Roles</h6>
                                    @foreach($roles as $rol)
                                        @php
                                            $count = $usuarios->filter(function($user) use ($rol) {
                                                return $user->roles->contains('id', $rol->id);
                                            })->count();
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span>{{ $rol->nombre }}</span>
                                            <span class="badge bg-primary">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Usuarios del Tenant</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Último Acceso</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            @if($usuario->foto_perfil)
                                                <img src="{{ Storage::url($usuario->foto_perfil->url) }}"
                                                     alt="Foto {{ $usuario->nombres }}"
                                                     class="rounded-circle"
                                                     width="32" height="32">
                                            @else
                                                <div
                                                    class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <strong>{{ $usuario->nombres }} {{ $usuario->apellidos }}</strong><br>
                                            <small class="text-muted">{{ $usuario->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form
                                        action="{{ route('superadmin.tenant.usuarios.cambiar-rol', ['tenantId' => $tenant->id, 'usuarioId' => $usuario->id]) }}"
                                        method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <select class="form-select form-select-sm user-role-select"
                                                name="rol_id"
                                                onchange="this.form.submit()">
                                            @foreach($roles as $rol)
                                                <option value="{{ $rol->id }}"
                                                    {{ $usuario->roles->contains('id', $rol->id) ? 'selected' : '' }}>
                                                    {{ $rol->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('superadmin.tenant.usuarios.toggle-estado', ['tenantId' => $tenant->id, 'usuarioId' => $usuario->id]) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="btn btn-sm {{ $usuario->activo ? 'btn-success' : 'btn-danger' }} estado-usuario-btn"
                                                data-usuario-id="{{ $usuario->id }}"
                                                data-estado-actual="{{ $usuario->activo ? '1' : '0' }}">
                                            <i class="bi {{ $usuario->activo ? 'bi-person-check-fill' : 'bi-person-x-fill' }} me-1"></i>
                                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    {{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->diffForHumans() : 'Nunca' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-people fs-2 mb-2"></i>
                                        <p class="mb-0">No hay usuarios registrados</p>
                                        <small>Agregue usuarios usando el botón "Agregar Usuario"</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Agregar Usuario -->
    <div class="modal fade" id="agregarUsuarioModal" tabindex="-1" aria-labelledby="agregarUsuarioModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('superadmin.tenant.usuarios.store', $tenant->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarUsuarioModalLabel">Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   required
                                   placeholder="ej. nuevo.usuario@empresa.com">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label"><i class="bi bi-person me-1"></i>Nombres <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="nombres"
                                       name="nombres"
                                       required
                                       placeholder="ej. Juan">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label"><i class="bi bi-person me-1"></i>Apellidos <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="apellidos"
                                       name="apellidos"
                                       required
                                       placeholder="ej. Pérez">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="celular" class="form-label"><i class="bi bi-phone me-1"></i>Celular</label>
                            <input type="tel"
                                   class="form-control"
                                   id="celular"
                                   name="celular"
                                   placeholder="ej. +51 987 654 321">
                        </div>
                        <div class="mb-3">
                            <label for="rol_id" class="form-label"><i class="bi bi-person-gear me-1"></i>Rol <span class="text-danger">*</span></label>
                            <select class="form-select"
                                    id="rol_id"
                                    name="rol_id"
                                    required>
                                <option value="">Seleccione un rol...</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>Contraseña <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Ingrese contraseña">
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword()">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Tenant -->
    <div class="modal fade" id="editarTenantModal" tabindex="-1" aria-labelledby="editarTenantModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarTenant" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarTenantModalLabel">
                            <i class="bi bi-building-gear me-2"></i>Editar Tenant
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="dominio" class="form-label"><i class="bi bi-globe me-1"></i>Dominio</label>
                                <input type="text" class="form-control" id="dominio" name="dominio" required placeholder="ej. miempresa">
                            </div>
                            <div class="col-md-6">
                                <label for="plan_suscripcion_id" class="form-label"><i class="bi bi-box-seam me-1"></i>Plan de Suscripción</label>
                                <select class="form-select" id="plan_suscripcion_id" name="plan_suscripcion_id"
                                        required>
                                    <option value="">Seleccione un plan...</option>
                                    @foreach($planes as $plan)
                                        <option value="{{ $plan->id }}"
                                                data-precio="{{ $plan->precio }}"
                                                data-intervalo="{{ $plan->intervalo }}"
                                                data-caracteristicas="{{ json_encode($plan->caracteristicas) }}">
                                            {{ $plan->nombre }} - {{ $plan->precio }}/{{ $plan->intervalo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nombre_empresa" class="form-label"><i class="bi bi-building me-1"></i>Nombre de la Empresa</label>
                                <input type="text" class="form-control" id="nombre_empresa"
                                       name="datos_contacto[nombre_empresa]" required placeholder="ej. Mi Empresa">
                            </div>
                            <div class="col-md-6">
                                <label for="editEmail" class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                                <input type="email" class="form-control" id="editEmail" name="datos_contacto[email]"
                                       required placeholder="ej. contacto@miempresa.com">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label"><i class="bi bi-phone me-1"></i>Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="datos_contacto[telefono]" placeholder="ej. +51 987 654 321">
                            </div>
                            <div class="col-md-6">
                                <label for="direccion" class="form-label"><i class="bi bi-geo-alt me-1"></i>Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="datos_contacto[direccion]" placeholder="ej. Av. Principal 123">
                            </div>
                            <div class="col-md-6">
                                <label for="logo" class="form-label"><i class="bi bi-image me-1"></i>Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <div id="logo-preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-toggle-on me-1"></i>Estado</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1">
                                    <label class="form-check-label" for="activo">Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
{{--        <script src="{{ asset('js/super-admin/tenant-show.js') }}"></script>--}}
    @endpush
@endsection
