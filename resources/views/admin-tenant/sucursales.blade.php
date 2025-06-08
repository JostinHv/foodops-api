@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Sucursales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-tenant/sucursales.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Sucursales</h1>
                <p class="mb-0 text-muted">Gestiona todas las sucursales de tus restaurantes</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaSucursalModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Sucursales</div>
                        <h3 class="mb-1">{{ $sucursales?->count() ?? 0 }}</h3>
                        <small class="text-muted">En {{ $restaurantes?->count() ?? 0 }} restaurantes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Capacidad Total</div>
                        <h3 class="mb-1">{{ $sucursales?->sum('capacidad_total') ?? 0 }}</h3>
                        <small class="text-muted">Personas en todas las sucursales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Sucursales Activas</div>
                        <h3 class="mb-1">{{ $sucursales?->where('activo', true)->count() ?? 0 }}</h3>
                        <small class="text-success">Operativas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Gerentes Asignados</div>
                        <h3 class="mb-1">{{ $sucursales?->whereNotNull('usuario_id')->count() ?? 0 }}</h3>
                        <small class="text-primary">Sucursales con gerente</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de sucursales -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Sucursales</h5>
                <span class="badge bg-primary">{{ $sucursales?->count() ?? 0 }} sucursales encontradas</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Restaurante</th>
                            <th>Gerente</th>
                            <th>Ubicación</th>
                            <th>Capacidad</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sucursales ?? [] as $sucursal)
                            <tr>
                                <td>
                                    <strong>{{ $sucursal->nombre }}</strong>
                                    <div><small class="text-muted">{{ $sucursal->tipo }}</small></div>
                                </td>
                                <td>{{ $sucursal->restaurante?->nombre_legal ?? 'No asignado' }}</td>
                                <td>
                                    <div class="sucursal-contacto">
                                        @if($sucursal->usuario)
                                            <div>{{ $sucursal->usuario->nombres }} {{ $sucursal->usuario->apellidos }}</div>
                                            <small class="text-muted">{{ $sucursal->usuario->email }}</small>
                                        @else
                                            <div>No asignado</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $sucursal->direccion ?? 'No especificada' }}</div>
                                    @if($sucursal->latitud && $sucursal->longitud)
                                        <small class="text-muted">{{ $sucursal->latitud }}
                                            , {{ $sucursal->longitud }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $sucursal->capacidad_total ? $sucursal->capacidad_total . ' personas' : 'No especificada' }}</div>
                                </td>
                                <td>
                                    @if($sucursal->hora_apertura && $sucursal->hora_cierre)
                                        <div>{{ Carbon::parse($sucursal->hora_apertura)->format('g:i A') }}
                                            - {{ Carbon::parse($sucursal->hora_cierre)->format('g:i A') }}</div>
                                    @else
                                        <div>No especificado</div>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('tenant.sucursales.toggle-activo', $sucursal) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit"
                                                class="btn btn-sm {{ $sucursal->activo ? 'btn-success' : 'btn-warning' }}">
                                            {{ $sucursal->activo ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles"
                                                data-bs-toggle="modal" data-bs-target="#verSucursalModal"
                                                data-sucursal="{{ $sucursal->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarSucursalModal"
                                                data-sucursal="{{ $sucursal->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay sucursales registradas</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Crear Nueva Sucursal -->
    <div class="modal fade" id="nuevaSucursalModal" tabindex="-1" aria-labelledby="nuevaSucursalModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tenant.sucursales.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="nuevaSucursalModalLabel">
                            <i class="bi bi-plus-circle me-2"></i>Crear Nueva Sucursal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="restaurante_id" class="form-label">Restaurante</label>
                                <select name="restaurante_id" class="form-select" required>
                                    <option value="">Seleccionar restaurante</option>
                                    @foreach($restaurantes ?? [] as $restaurante)
                                        <option value="{{ $restaurante->id }}">{{ $restaurante->nombre_legal }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_id" class="form-label">Gerente</label>
                                <select name="usuario_id" class="form-select">
                                    <option value="">Seleccionar gerente</option>
                                    @foreach($gerentes ?? [] as $gerente)
                                        <option value="{{ $gerente->id }}">
                                            {{ $gerente->nombres }} {{ $gerente->apellidos }} - {{ $gerente->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre de la Sucursal</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" name="tipo" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" name="latitud" class="form-control" step="any">
                            </div>
                            <div class="col-md-4">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" name="longitud" class="form-control" step="any">
                            </div>
                            <div class="col-md-4">
                                <label for="capacidad_total" class="form-label">Capacidad Total</label>
                                <input type="number" name="capacidad_total" class="form-control" min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hora_apertura" class="form-label">Hora de Apertura</label>
                                <input type="time" name="hora_apertura" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="hora_cierre" class="form-label">Hora de Cierre</label>
                                <input type="time" name="hora_cierre" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-plus-circle me-2"></i>Crear Sucursal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Sucursal -->
    <div class="modal fade" id="editarSucursalModal" tabindex="-1" aria-labelledby="editarSucursalModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarSucursal" action="" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="editarSucursalModalLabel">
                            <i class="bi bi-pencil me-2"></i>Editar Sucursal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="restaurante_id" class="form-label">Restaurante</label>
                                <select name="restaurante_id" class="form-select" required>
                                    <option value="">Seleccionar restaurante</option>
                                    @foreach($restaurantes ?? [] as $restaurante)
                                        <option value="{{ $restaurante->id }}">{{ $restaurante->nombre_legal }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_id" class="form-label">Gerente</label>
                                <select name="usuario_id" class="form-select">
                                    <option value="">Seleccionar gerente</option>
                                    @foreach($gerentes ?? [] as $gerente)
                                        <option value="{{ $gerente->id }}">
                                            {{ $gerente->nombres }} {{ $gerente->apellidos }} - {{ $gerente->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre de la Sucursal</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" name="tipo" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" name="latitud" class="form-control" step="any">
                            </div>
                            <div class="col-md-4">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" name="longitud" class="form-control" step="any">
                            </div>
                            <div class="col-md-4">
                                <label for="capacidad_total" class="form-label">Capacidad Total</label>
                                <input type="number" name="capacidad_total" class="form-control" min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hora_apertura" class="form-label">Hora de Apertura</label>
                                <input type="time" name="hora_apertura" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="hora_cierre" class="form-label">Hora de Cierre</label>
                                <input type="time" name="hora_cierre" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Ver Detalles Sucursal -->
    <div class="modal fade" id="verSucursalModal" tabindex="-1" aria-labelledby="verSucursalModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="verSucursalModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Detalles de la Sucursal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <i class="bi bi-building me-2"></i>Información General
                        </h6>
                        <div class="ps-3">
                            <p id="sucursal-nombre" class="mb-2"></p>
                            <p id="sucursal-restaurante" class="mb-2"></p>
                            <p id="sucursal-tipo" class="mb-2"></p>
                            <p id="sucursal-estado" class="badge mb-3"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <i class="bi bi-geo-alt me-2"></i>Ubicación
                        </h6>
                        <div class="ps-3">
                            <p id="sucursal-direccion" class="mb-2"></p>
                            <p id="sucursal-ubicacion" class="mb-2"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <i class="bi bi-person me-2"></i>Contacto
                        </h6>
                        <div class="ps-3">
                            <p id="sucursal-gerente" class="mb-2"></p>
                            <p id="sucursal-telefono" class="mb-2"></p>
                            <p id="sucursal-email" class="mb-2"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <i class="bi bi-clock me-2"></i>Detalles Operativos
                        </h6>
                        <div class="ps-3">
                            <p id="sucursal-capacidad" class="mb-2"></p>
                            <p id="sucursal-horario" class="mb-2"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-tenant/sucursales.js') }}"></script>
@endpush
