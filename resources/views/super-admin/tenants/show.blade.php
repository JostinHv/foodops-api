@extends('layouts.app')

@section('title', 'Detalles del Tenant')

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
                            <th class="text-end">Acciones</th>
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
                                        <select class="form-select form-select-sm"
                                                name="rol_id"
                                                onchange="this.form.submit()"
                                                style="width: 140px;">
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
                                    <span class="badge {{ $usuario->activo ? 'bg-success' : 'bg-danger' }}">
                                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->diffForHumans() : 'Nunca' }}
                                </td>
                                <td class="text-end">
                                    <form
                                        action="{{ route('superadmin.tenant.usuarios.destroy', ['tenantId' => $tenant->id, 'usuarioId' => $usuario->id]) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
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
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="nombres"
                                       name="nombres"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="apellidos"
                                       name="apellidos"
                                       required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="tel"
                                   class="form-control"
                                   id="celular"
                                   name="celular">
                        </div>
                        <div class="mb-3">
                            <label for="rol_id" class="form-label">Rol <span class="text-danger">*</span></label>
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
                            <label for="password" class="form-label">Contraseña <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required>
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
                                <label for="dominio" class="form-label">Dominio</label>
                                <input type="text" class="form-control" id="dominio" name="dominio" required>
                            </div>
                            <div class="col-md-6">
                                <label for="plan_suscripcion_id" class="form-label">Plan de Suscripción</label>
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
                                <label for="nombre_empresa" class="form-label">Nombre de la Empresa</label>
                                <input type="text" class="form-control" id="nombre_empresa"
                                       name="datos_contacto[nombre_empresa]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="datos_contacto[email]"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="datos_contacto[telefono]">
                            </div>
                            <div class="col-md-6">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="datos_contacto[direccion]">
                            </div>
                            <div class="col-md-6">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <div id="logo-preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
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
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const icon = document.querySelector('.input-group button i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Manejar el modal de edición
                const editarTenantModal = document.getElementById('editarTenantModal');
                if (editarTenantModal) {
                    const modal = new bootstrap.Modal(editarTenantModal);

                    editarTenantModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const tenantId = button.getAttribute('data-tenant-id');
                        const form = this.querySelector('form');

                        // Actualizar la URL del formulario
                        form.action = `/superadmin/tenant/${tenantId}`;

                        // Llenar los campos del formulario
                        document.getElementById('dominio').value = button.getAttribute('data-tenant-dominio');
                        document.getElementById('nombre_empresa').value = button.getAttribute('data-tenant-nombre');
                        document.getElementById('email').value = button.getAttribute('data-tenant-email');
                        document.getElementById('telefono').value = button.getAttribute('data-tenant-telefono');
                        document.getElementById('direccion').value = button.getAttribute('data-tenant-direccion');
                        document.getElementById('activo').checked = button.getAttribute('data-tenant-activo') === '1';

                        // Cargar el plan actual del tenant
                        fetch(`/superadmin/tenant/${tenantId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.tenant && data.tenant.plan_suscripcion_id) {
                                    document.getElementById('plan_suscripcion_id').value = data.tenant.plan_suscripcion_id;
                                }
                            })
                            .catch(error => console.error('Error al cargar el plan:', error));
                    });
                }

                // Previsualización del logo
                const logoInput = document.getElementById('logo');
                const logoPreview = document.getElementById('logo-preview');

                if (logoInput && logoPreview) {
                    logoInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                logoPreview.innerHTML = `
                        <img src="${e.target.result}"
                            class="img-thumbnail"
                            style="max-height: 100px"
                            alt="Logo preview">`;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                // Cerrar automáticamente los mensajes de alerta después de 5 segundos
                const alerts = document.querySelectorAll('.alert.alert-dismissible');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.classList.add('fade');
                        setTimeout(() => alert.remove(), 150);
                    }, 5000);
                });
            });
        </script>
    @endpush

@endsection
