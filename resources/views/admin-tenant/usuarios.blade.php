@extends('layouts.app')

@section('title', 'Usuarios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-tenant/usuarios.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Trabajadores</h1>
                <p class="mb-0 text-muted">Gestiona todos los trabajadores de tu organización</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalUsuario">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Trabajadores</div>
                        <h3 class="mb-1">{{ $totalUsuarios ?? 0}}</h3>
                        <small class="text-muted">Usuarios registrados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Trabajadores Activos</div>
                        <h3 class="mb-1">{{ $usuariosActivos ?? 0 }}</h3>
                        <small class="text-success">{{ round(($usuariosActivos / $totalUsuarios ?? 1) * 100) }}% del
                            total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Gerentes</div>
                        <h3 class="mb-1">{{ $gerentes ?? 0 }}</h3>
                        <small class="text-primary">Gerentes de sucursal</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Personal Operativo</div>
                        <h3 class="mb-1">{{ $personalOperativo ?? 0  }}</h3>
                        <small class="text-info">Meseros, cajeros y cocineros</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de usuarios -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Usuarios</h5>
                <span class="badge bg-primary">{{ $usuarios?->count() ?? 0  }} usuarios encontrados</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Restaurante</th>
                            <th>Sucursal Asignada</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th>Último Acceso</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($usuarios ?? [] as $usuario)
                            <tr>
                                <td>
                                    <strong>{{ $usuario->nombres }} {{ $usuario->apellidos }}</strong>
                                    <div class="text-muted">{{ $usuario->email }}</div>
                                </td>
                                <td>
                                    @foreach($usuario->roles as $rol)
                                        <span class="badge bg-info">{{ $rol->nombre }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $usuario->restaurante?->nombre_legal ?? 'No asignado' }}</td>
                                <td>
                                    @if($usuario->asignacionesPersonal->isNotEmpty())
                                        {{ $usuario->asignacionesPersonal->first()->sucursal->nombre }}
                                        <small class="text-muted d-block">
                                            Tipo: {{ $usuario->asignacionesPersonal->first()->tipo }}
                                        </small>
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $usuario->celular ?? 'No especificado' }}</div>
                                </td>
                                <td>
                                    <form action="{{ route('tenant.usuarios.toggle-activo', $usuario) }}" method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit"
                                                class="btn btn-sm {{ $usuario->activo ? 'btn-success' : 'btn-warning' }}">
                                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($usuario->ultimo_acceso)
                                        <div>{{ $usuario->ultimo_acceso->format('d/m/Y') }}</div>
                                    @else
                                        <div class="text-muted">Nunca</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary btn-ver" title="Ver detalles"
                                                data-bs-toggle="modal" data-bs-target="#verUsuarioModal"
                                                data-usuario="{{ $usuario->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary btn-editar" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#modalEditarUsuario"
                                                data-usuario="{{ $usuario->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Crear Nuevo Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-white">
                    <h5 class="modal-title" id="modalUsuarioLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form id="formUsuario" method="POST" action="{{ route('tenant.usuarios.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">
                                    <i class="bi bi-person me-2"></i>Nombres
                                </label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                       placeholder="Ej: Juan Carlos" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">
                                    <i class="bi bi-person me-2"></i>Apellidos
                                </label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                       placeholder="Ej: Pérez García" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="Ej: juan.perez@ejemplo.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="celular" class="form-label">
                                    <i class="bi bi-phone me-2"></i>Celular
                                </label>
                                <input type="tel" class="form-control" id="celular" name="celular"
                                       placeholder="Ej: 987654321">
                            </div>
                            <div class="col-md-6">
                                <label for="rol_id" class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>Rol
                                </label>
                                <select class="form-select" id="rol_id" name="rol_id" required>
                                    <option value="">Seleccione un rol</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sucursal_id" class="form-label">
                                    <i class="bi bi-shop me-2"></i>Sucursal Asignada
                                </label>
                                <select class="form-select" id="sucursal_id" name="sucursal_id">
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}"
                                                data-restaurante="{{ $sucursal->restaurante->nombre_legal }}"
                                                data-direccion="{{ $sucursal->direccion }}"
                                                data-capacidad="{{ $sucursal->capacidad_total }}">
                                            {{ $sucursal->nombre }} - {{ $sucursal->restaurante->nombre_legal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12" id="info-sucursal" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>Información de la Sucursal
                                    </h6>
                                    <div id="sucursal-info-content"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="notas_asignacion" class="form-label">
                                    <i class="bi bi-sticky me-2"></i>Notas de Asignación
                                </label>
                                <textarea class="form-control" id="notas_asignacion" name="notas_asignacion" rows="2"
                                          placeholder="Información adicional sobre la asignación del personal (ej: turno de trabajo, responsabilidades específicas)"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key me-2"></i>Contraseña
                                </label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Mínimo 8 caracteres" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-key-fill me-2"></i>Confirmar Contraseña
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" placeholder="Repita la contraseña" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-white">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">
                        <i class="bi bi-person-fill-gear me-2"></i>Editar Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form id="formEditarUsuario" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_nombres" class="form-label">
                                    <i class="bi bi-person me-2"></i>Nombres
                                </label>
                                <input type="text" class="form-control" id="edit_nombres" name="nombres" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_apellidos" class="form-label">
                                    <i class="bi bi-person me-2"></i>Apellidos
                                </label>
                                <input type="text" class="form-control" id="edit_apellidos" name="apellidos" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_celular" class="form-label">
                                    <i class="bi bi-phone me-2"></i>Celular
                                </label>
                                <input type="tel" class="form-control" id="edit_celular" name="celular">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_rol_id" class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>Rol
                                </label>
                                <select class="form-select" id="edit_rol_id" name="rol_id" required>
                                    <option value="">Seleccione un rol</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_sucursal_id" class="form-label">
                                    <i class="bi bi-shop me-2"></i>Sucursal Asignada
                                </label>
                                <select class="form-select" id="edit_sucursal_id" name="sucursal_id">
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}"
                                                data-restaurante="{{ $sucursal->restaurante->nombre_legal }}"
                                                data-direccion="{{ $sucursal->direccion }}"
                                                data-capacidad="{{ $sucursal->capacidad_total }}">
                                            {{ $sucursal->nombre }} - {{ $sucursal->restaurante->nombre_legal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12" id="edit-info-sucursal" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>Información de la Sucursal
                                    </h6>
                                    <div id="edit-sucursal-info-content"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="edit_notas_asignacion" class="form-label">
                                    <i class="bi bi-sticky me-2"></i>Notas de Asignación
                                </label>
                                <textarea class="form-control" id="edit_notas_asignacion" name="notas_asignacion"
                                          rows="2"
                                          placeholder="Información adicional sobre la asignación del personal"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_password" class="form-label">
                                    <i class="bi bi-key me-2"></i>Nueva Contraseña
                                </label>
                                <input type="password" class="form-control" id="edit_password" name="password"
                                       placeholder="Dejar en blanco para mantener la actual">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_password_confirmation" class="form-label">
                                    <i class="bi bi-key-fill me-2"></i>Confirmar Nueva Contraseña
                                </label>
                                <input type="password" class="form-control" id="edit_password_confirmation"
                                       name="password_confirmation" placeholder="Repita la nueva contraseña">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Ver Detalles Usuario -->
    <div class="modal fade" id="verUsuarioModal" tabindex="-1" aria-labelledby="verUsuarioModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-white ">
                    <h5 class="modal-title" id="verUsuarioModalLabel">
                        <i class="bi bi-person-vcard me-2"></i>Detalles del Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2">
                                    <i class="bi bi-person me-2"></i>Información Personal
                                </h6>
                                <div class="ps-3">
                                    <p id="usuario-nombre" class="mb-2"></p>
                                    <p id="usuario-email" class="mb-2"></p>
                                    <p id="usuario-celular" class="mb-2"></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="border-bottom pb-2">
                                    <i class="bi bi-building me-2"></i>Información Laboral
                                </h6>
                                <div class="ps-3">
                                    <p id="usuario-restaurante" class="mb-2"></p>
                                    <p id="usuario-rol" class="mb-2"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2">
                                    <i class="bi bi-shop me-2"></i>Asignación Actual
                                </h6>
                                <div class="ps-3">
                                    <p id="usuario-sucursal" class="mb-2"></p>
                                    <p id="usuario-tipo-asignacion" class="mb-2"></p>
                                    <p id="usuario-notas-asignacion" class="mb-2"></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="border-bottom pb-2">
                                    <i class="bi bi-clock-history me-2"></i>Información de Acceso
                                </h6>
                                <div class="ps-3">
                                    <p id="usuario-estado" class="badge mb-2"></p>
                                    <p id="usuario-ultimo-acceso" class="mb-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-tenant/usuarios.js') }}"></script>
@endpush

