@extends('layouts.app')

@section('title', 'Mesas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gerente-sucursal/mesas.css') }}">
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
                <h1 class="h3 mb-0">Gestión de Mesas</h1>
                <p class="mb-0 text-muted">Administra el estado y configuración de mesas</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaMesaModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Mesa
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Mesas</div>
                        <h3 class="mb-1">{{ $totalMesas }}</h3>
                        <small class="text-muted">{{ $totalAsientos }} asientos totales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Ocupación</div>
                        <h3 class="mb-1">{{ number_format($ocupacion, 1) }}%</h3>
                        <small class="text-muted">
                            {{ $mesas->where('estadoMesa.nombre', 'Ocupada')->count() }} de {{ $totalMesas }} mesas
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Asientos Disponibles</div>
                        <h3 class="mb-1">{{ $totalAsientos - ($mesas->where('estadoMesa.nombre', 'Ocupada')->sum('capacidad')) }}</h3>
                        <small class="text-muted">de {{ $totalAsientos }} asientos totales</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de mesas -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mesas del Restaurante</h5>
                <span class="badge bg-primary">{{ $totalMesas }} mesas encontradas</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Mesa</th>
                            <th>Sucursal</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($mesas as $mesa)
                            <tr>
                                <td><strong>{{ $mesa['nombre'] }}</strong></td>
                                <td>{{ $mesa['sucursal']['nombre'] }}</td>
                                <td>{{ $mesa['capacidad'] }} personas</td>
                                <td>
                                    @php
                                        $badgeClass = match($mesa->estadoMesa->nombre ?? 'Desconocido') {
                                            'Libre' => 'bg-success',
                                            'Ocupada' => 'bg-warning text-dark',
                                            'Reservada' => 'bg-info',
                                            'Sucia' => 'bg-danger',
                                            'En Limpieza' => 'bg-secondary',
                                            'Bloqueada' => 'bg-dark',
                                            default => 'bg-primary'
                                        };
                                    @endphp
                                    <span
                                            class="badge {{ $badgeClass }}">{{ $mesa->estadoMesa->nombre ?? 'Desconocido'}}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verMesaModal"
                                                data-mesa="{{ $mesa['id'] }}"
                                                title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarMesaModal"
                                                data-mesa="{{ $mesa['id'] }}"
                                                title="Editar mesa">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        <p class="mb-0">No hay mesas registradas</p>
                                        <small>Crea una nueva mesa usando el botón superior</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal: Nueva Mesa -->
        <div class="modal fade" id="nuevaMesaModal" tabindex="-1" aria-labelledby="nuevaMesaModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formNuevaMesa" action="{{ route('gerente.mesas.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="nuevaMesaModalLabel">Nueva Mesa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">
                                        <i class="bi bi-tag me-1"></i>Nombre
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="nombre"
                                           name="nombre"
                                           placeholder="Ej: Mesa VIP 1"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="capacidad" class="form-label">
                                        <i class="bi bi-people me-1"></i>Capacidad
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="capacidad"
                                           name="capacidad"
                                           min="1"
                                           max="20"
                                           placeholder="Ej: 4"
                                           required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="sucursal_id" class="form-label">
                                    <i class="bi bi-shop me-1"></i>Sucursal
                                </label>
                                <select class="form-select" id="sucursal_id" name="sucursal_id" required>
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="estado_mesa_id" class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i>Estado Inicial
                                </label>
                                <select class="form-select" id="estado_mesa_id" name="estado_mesa_id" required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach($estadosMesa as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-check-circle me-2"></i>Crear Mesa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Editar Mesa -->
        <div class="modal fade" id="editarMesaModal" tabindex="-1" aria-labelledby="editarMesaModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formEditarMesa" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarMesaModalLabel">Editar Mesa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="edit_nombre" class="form-label">
                                        <i class="bi bi-tag me-1"></i>Nombre
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="edit_nombre"
                                           name="nombre"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_capacidad" class="form-label">
                                        <i class="bi bi-people me-1"></i>Capacidad
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="edit_capacidad"
                                           name="capacidad"
                                           min="1"
                                           max="20"
                                           required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_sucursal_id" class="form-label">
                                    <i class="bi bi-shop me-1"></i>Sucursal
                                </label>
                                <select class="form-select" id="edit_sucursal_id" name="sucursal_id" required>
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_estado_mesa_id" class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i>Estado
                                </label>
                                <select class="form-select" id="edit_estado_mesa_id" name="estado_mesa_id" required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach($estadosMesa as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Ver Mesa -->
        <div class="modal fade" id="verMesaModal" tabindex="-1" aria-labelledby="verMesaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verMesaModalLabel">Detalles de la Mesa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6><i class="bi bi-tag me-1"></i>Nombre</h6>
                            <p id="mesa-nombre"></p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-shop me-1"></i>Sucursal</h6>
                            <p id="mesa-sucursal"></p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-people me-1"></i>Capacidad</h6>
                            <p id="mesa-capacidad"></p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-toggle-on me-1"></i>Estado</h6>
                            <p id="mesa-estado"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/gerente-sucursal/mesas.js') }}"></script>
    @endpush
@endsection
