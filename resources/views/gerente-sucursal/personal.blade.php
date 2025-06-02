@extends('layouts.app')

@section('title', 'Personal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gerente-sucursal/personal.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Gestión de Personal</h1>
                <p class="mb-0 text-muted">Administra empleados </p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoPersonalModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Empleado
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Personal Activo</div>
                        <h3 class="mb-1">{{ $usuarios->where('activo', true)->count() }}</h3>
                        <small class="text-success">Total de empleados activos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Meseros</div>
                        <h3 class="mb-1">{{ $asignaciones->where('tipo', 'mesero')->where('activo', true)->count() }}</h3>
                        <small class="text-muted">En servicio</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Cocineros</div>
                        <h3 class="mb-1">{{ $asignaciones->where('tipo', 'cocinero')->where('activo', true)->count() }}</h3>
                        <small class="text-muted">En servicio</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Cajeros</div>
                        <h3 class="mb-1">{{ $asignaciones->where('tipo', 'cajero')->where('activo', true)->count() }}</h3>
                        <small class="text-muted">En servicio</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de empleados -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Personal Activo</h5>
                <div>
                    <span class="badge bg-primary me-2">{{ $usuarios->count() }} empleados</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Contacto</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($usuarios as $usuario)
                            @php
                                $asignacion = $asignaciones->where('usuario_id', $usuario->id)->first();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $usuario->nombres }} {{ $usuario->apellidos }}</strong>
                                    <div class="text-muted small">
                                        Nro: {{ $usuario->nro_celular ?: 'No especificado' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $asignacion->tipo ? $roles[$asignacion->tipo] : 'No asignado' }}</span>
                                </td>
                                <td>
                                    <div>{{ $usuario->email }}</div>
                                    <div class="text-muted small">{{ $usuario->celular ?: 'No especificado' }}</div>
                                </td>
                                <td>
                                    {{ $asignacion->sucursal->nombre ?? 'No asignada' }}
                                </td>
                                <td>
                                    <span class="badge {{ $usuario->activo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                                title="Ver detalles"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verPersonalModal"
                                                data-personal="{{ $usuario->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                                title="Editar"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarPersonalModal"
                                                data-personal="{{ $usuario->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button
                                                class="btn btn-sm {{ $usuario->activo ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                title="{{ $usuario->activo ? 'Desactivar' : 'Activar' }}"
                                                data-action="toggle-activo"
                                                data-personal="{{ $usuario->id }}">
                                            <i class="bi {{ $usuario->activo ? 'bi-person-dash' : 'bi-person-plus' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        <p class="mb-0">No hay personal registrado</p>
                                        <small>Agrega nuevo personal usando el botón superior</small>
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

    <!-- Modal: Nuevo Personal -->
    <div class="modal fade" id="nuevoPersonalModal" tabindex="-1" aria-labelledby="nuevoPersonalModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formNuevoPersonal" action="{{ route('gerente.personal.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoPersonalModalLabel">Nuevo Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="celular" class="form-label">Celular</label>
                                <input type="tel" class="form-control" id="celular" name="celular" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="sucursal_id" class="form-label">Sucursal</label>
                                <select class="form-select" id="sucursal_id" name="sucursal_id" required>
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($roles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">Crear Empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Personal -->
    <div class="modal fade" id="editarPersonalModal" tabindex="-1" aria-labelledby="editarPersonalModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarPersonal" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarPersonalModalLabel">Editar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="edit_nombres" name="nombres" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="edit_apellidos" name="apellidos" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_celular" class="form-label">Celular</label>
                                <input type="tel" class="form-control" id="edit_celular" name="celular" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_password" class="form-label">Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_password" name="password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_sucursal_id" class="form-label">Sucursal</label>
                                <select class="form-select" id="edit_sucursal_id" name="sucursal_id" required>
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="edit_tipo" name="tipo" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($roles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Ver Personal -->
    <div class="modal fade" id="verPersonalModal" tabindex="-1" aria-labelledby="verPersonalModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verPersonalModalLabel">Detalles del Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6><i class="bi bi-person me-1"></i>Nombre Completo</h6>
                        <p id="personal-nombre"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-envelope me-1"></i>Email</h6>
                        <p id="personal-email"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-telephone me-1"></i>Celular</h6>
                        <p id="personal-celular"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-shop me-1"></i>Sucursal</h6>
                        <p id="personal-sucursal"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-person-badge me-1"></i>Tipo</h6>
                        <p id="personal-tipo"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-toggle-on me-1"></i>Estado</h6>
                        <p id="personal-estado"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/gerente-sucursal/personal.js') }}"></script>
        <script>
            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                const icon = input.nextElementSibling.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        </script>
    @endpush
@endsection
