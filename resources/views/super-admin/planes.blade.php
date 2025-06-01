@extends('layouts.app')

@section('title', 'Planes de Suscripción')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/planes.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Planes de Suscripción</h1>
                <p class="mb-0 text-muted">Gestiona los planes de suscripción disponibles</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoPlanModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Plan
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Planes</div>
                        <h3 class="mb-1">{{ $estadisticas['total_planes'] }}</h3>
                        <small class="text-muted">Planes disponibles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Planes Activos</div>
                        <h3 class="mb-1">{{ $estadisticas['planes_activos'] }}</h3>
                        <small class="text-success">En uso</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Ingresos Mensuales</div>
                        <h3 class="mb-1">S/ {{ number_format($estadisticas['ingresos_mensuales'], 2) }}</h3>
                        <small class="text-primary">Por suscripciones mensuales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Ingresos Anuales</div>
                        <h3 class="mb-1">S/ {{ number_format($estadisticas['ingresos_anuales'], 2) }}</h3>
                        <small class="text-primary">Por suscripciones anuales</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Planes Mensuales -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-month me-2"></i>Planes Mensuales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($planesMensuales as $plan)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 plan-card">
                                <div class="card-header {{ $plan->activo ? 'bg-primary' : 'bg-secondary' }} text-white">
                                    <h5 class="mb-0">{{ $plan->nombre }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <h2 class="mb-0">S/ {{ number_format($plan->precio, 2) }}</h2>
                                        <small class="text-muted">por mes</small>
                                    </div>
                                    <p class="text-muted mb-4">{{ $plan->descripcion }}</p>
                                    <ul class="list-unstyled mb-4">
                                        @foreach($plan->caracteristicas as $caracteristica)
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                {{ $caracteristica }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Límite de Usuarios:</span>
                                            <strong>{{ $plan->limite_usuarios }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Límite de Restaurantes:</span>
                                            <strong>{{ $plan->limite_restaurantes }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Límite de Sucursales:</span>
                                            <strong>{{ $plan->limite_sucursales }}</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarPlanModal"
                                                data-plan="{{ $plan->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('superadmin.planes.toggle-activo', $plan) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $plan->activo ? 'btn-success' : 'btn-warning' }}">
                                                {{ $plan->activo ? 'Activo' : 'Inactivo' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                No hay planes mensuales disponibles
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Planes Anuales -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-year me-2"></i>Planes Anuales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($planesAnuales as $plan)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 plan-card">
                                <div class="card-header {{ $plan->activo ? 'bg-primary' : 'bg-secondary' }} text-white">
                                    <h5 class="mb-0">{{ $plan->nombre }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <h2 class="mb-0">S/ {{ number_format($plan->precio, 2) }}</h2>
                                        <small class="text-muted">por año</small>
                                    </div>
                                    <p class="text-muted mb-4">{{ $plan->descripcion }}</p>
                                    <ul class="list-unstyled mb-4">
                                        @foreach($plan->caracteristicas as $caracteristica)
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                {{ $caracteristica }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Límite de Usuarios:</span>
                                            <strong>{{ $plan->limite_usuarios }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Límite de Restaurantes:</span>
                                            <strong>{{ $plan->limite_restaurantes }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Límite de Sucursales:</span>
                                            <strong>{{ $plan->limite_sucursales }}</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarPlanModal"
                                                data-plan="{{ $plan->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('superadmin.planes.toggle-activo', $plan) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $plan->activo ? 'btn-success' : 'btn-warning' }}">
                                                {{ $plan->activo ? 'Activo' : 'Inactivo' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                No hay planes anuales disponibles
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Crear Nuevo Plan -->
    <div class="modal fade" id="nuevoPlanModal" tabindex="-1" aria-labelledby="nuevoPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('superadmin.planes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="nuevoPlanModalLabel">
                            <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Plan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Plan</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="intervalo" class="form-label">Intervalo</label>
                                <select name="intervalo" class="form-select" required>
                                    <option value="mes">Mensual</option>
                                    <option value="anual">Anual</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" name="precio" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="limite_usuarios" class="form-label">Límite de Usuarios</label>
                                <input type="number" name="limite_usuarios" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_restaurantes" class="form-label">Límite de Restaurantes</label>
                                <input type="number" name="limite_restaurantes" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_sucursales" class="form-label">Límite de Sucursales</label>
                                <input type="number" name="limite_sucursales" class="form-control" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Características</label>
                            <div id="caracteristicas-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="caracteristicas[]" class="form-control" required>
                                    <button type="button" class="btn btn-outline-danger" onclick="eliminarCaracteristica(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarCaracteristica()">
                                <i class="bi bi-plus-circle me-2"></i>Agregar Característica
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-plus-circle me-2"></i>Crear Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Plan -->
    <div class="modal fade" id="editarPlanModal" tabindex="-1" aria-labelledby="editarPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarPlan" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="editarPlanModalLabel">
                            <i class="bi bi-pencil me-2"></i>Editar Plan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Plan</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="intervalo" class="form-label">Intervalo</label>
                                <select name="intervalo" class="form-select" required>
                                    <option value="mes">Mensual</option>
                                    <option value="anual">Anual</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" name="precio" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="limite_usuarios" class="form-label">Límite de Usuarios</label>
                                <input type="number" name="limite_usuarios" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_restaurantes" class="form-label">Límite de Restaurantes</label>
                                <input type="number" name="limite_restaurantes" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_sucursales" class="form-label">Límite de Sucursales</label>
                                <input type="number" name="limite_sucursales" class="form-control" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Características</label>
                            <div id="caracteristicas-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="caracteristicas[]" class="form-control" required>
                                    <button type="button" class="btn btn-outline-danger" onclick="eliminarCaracteristica(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarCaracteristica()">
                                <i class="bi bi-plus-circle me-2"></i>Agregar Característica
                            </button>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/super-admin/planes.js') }}"></script>
@endpush
