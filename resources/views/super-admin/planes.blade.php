@extends('layouts.app')

@section('title', 'Planes de Suscripción')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
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
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPlanModal">
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
                        <small class="text-primary">Planes disponibles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Planes Activos</div>
                        <h3 class="mb-1">{{ $estadisticas['planes_activos'] }}</h3>
                        <small class="text-primary">En uso</small>
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
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-month me-2"></i>Planes Mensuales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($planesMensuales as $plan)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 plan-card">
                                <div class="card-header {{ $plan->activo ? 'bg-success' : 'bg-secondary' }} text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ $plan->nombre }}</h5>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-light" title="Editar"
                                                    data-bs-toggle="modal" data-bs-target="#editarPlanModal"
                                                    data-plan="{{ $plan->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('superadmin.planes.toggle-activo', $plan) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit"
                                                        class="btn btn-sm {{ $plan->activo ? 'btn-warning' : 'btn-info' }}">
                                                    <i class="bi {{ $plan->activo ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-4">{{ $plan->descripcion }}</p>

                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2">Límites del Plan</h6>
                                        <div class="row g-3">
                                            <div class="col-12 col-sm-4">
                                                <div
                                                    class="d-flex align-items-center justify-content-center justify-content-sm-start">
                                                    <div class="text-center text-sm-start">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="bi bi-people me-2"></i>
                                                            <small class="text-muted">Usuarios</small>
                                                        </div>
                                                        <div class="text-center">
                                                            <strong>{{ $plan->caracteristicas['limites']['usuarios'] ?? 0 }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div
                                                    class="d-flex align-items-center justify-content-center justify-content-sm-start">
                                                    <div class="text-center text-sm-start">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="bi bi-building me-2"></i>
                                                            <small class="text-muted">Restaurantes</small>
                                                        </div>
                                                        <div class="text-center">
                                                            <strong>{{ $plan->caracteristicas['limites']['restaurantes'] ?? 0 }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div
                                                    class="d-flex align-items-center justify-content-center justify-content-sm-start">
                                                    <div class="text-center text-sm-start">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="bi bi-shop me-2"></i>
                                                            <small class="text-muted">Sucursales</small>
                                                        </div>
                                                        <div class="text-center">
                                                            <strong>{{ $plan->caracteristicas['limites']['sucursales'] ?? 0 }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Características adicionales -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2">Características</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($plan->caracteristicas['adicionales'] ?? [] as $caracteristica)
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                    {{ $caracteristica }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Precio</span>
                                            <strong>S/. {{ number_format($plan->precio, 2) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Intervalo</span>
                                            <span class="badge bg-info">{{ ucfirst($plan->intervalo) }}</span>
                                        </div>
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
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-year me-2"></i>Planes Anuales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($planesAnuales as $plan)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 plan-card">
                                <div class="card-header {{ $plan->activo ? 'bg-success' : 'bg-secondary' }} text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ $plan->nombre }}</h5>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-light" title="Editar"
                                                    data-bs-toggle="modal" data-bs-target="#editarPlanModal"
                                                    data-plan="{{ $plan->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('superadmin.planes.toggle-activo', $plan) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit"
                                                        class="btn btn-sm {{ $plan->activo ? 'btn-warning' : 'btn-info' }}">
                                                    <i class="bi {{ $plan->activo ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $plan->nombre }}</h5>
                                    <p class="text-muted mb-4">{{ $plan->descripcion }}</p>

                                    <!-- Límites del plan -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2">Límites del Plan</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-people me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Usuarios</small>
                                                        <strong>{{ $plan->limite_usuarios }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-building me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Restaurantes</small>
                                                        <strong>{{ $plan->limite_restaurantes }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-shop me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Sucursales</small>
                                                        <strong>{{ $plan->limite_sucursales }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Características adicionales -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2">Características</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($plan->caracteristicas['adicionales'] ?? [] as $caracteristica)
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                    {{ $caracteristica }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Precio</span>
                                            <strong>S/. {{ number_format($plan->precio, 2) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Intervalo</span>
                                            <span class="badge bg-info">{{ ucfirst($plan->intervalo) }}</span>
                                        </div>
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
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="nuevoPlanModalLabel">
                            <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Plan
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Nombre del Plan
                                </label>
                                <input type="text" name="nombre" class="form-control"
                                       placeholder="Ejemplo: Plan Básico" required>
                            </div>
                            <div class="col-md-6">
                                <label for="precio" class="form-label">
                                    <i class="bi bi-currency-dollar me-1"></i>Precio
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="precio" class="form-control"
                                           step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="limite_usuarios" class="form-label">
                                    <i class="bi bi-people me-1"></i>Límite de Usuarios
                                </label>
                                <input type="number" name="limite_usuarios" class="form-control"
                                       min="0" placeholder="Ejemplo: 5" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_restaurantes" class="form-label">
                                    <i class="bi bi-building me-1"></i>Límite de Restaurantes
                                </label>
                                <input type="number" name="limite_restaurantes" class="form-control"
                                       min="0" placeholder="Ejemplo: 2" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_sucursales" class="form-label">
                                    <i class="bi bi-shop me-1"></i>Límite de Sucursales
                                </label>
                                <input type="number" name="limite_sucursales" class="form-control"
                                       min="0" placeholder="Ejemplo: 3" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Descripción
                            </label>
                            <textarea name="descripcion" class="form-control" rows="3"
                                      placeholder="Describe las características principales del plan..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="intervalo" class="form-label">
                                <i class="bi bi-clock me-1"></i>Intervalo
                            </label>
                            <select name="intervalo" class="form-select" required>
                                <option value="mes">Mensual</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info">
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Ingresa las características separadas por comas
                                </p>
                                <div class="form-floating">
                                    <textarea name="caracteristicas" class="form-control" rows="3"
                                              placeholder="Ejemplo: Gestión multi-sucursal, API personalizada, Reportes en tiempo real"
                                              required minlength="1" style="height: 100px"></textarea>
                                    <label>Características de adicionales</label>
                                </div>
                                @error('caracteristicas')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Crear Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Plan -->
    <div class="modal fade" id="editarPlanModal" tabindex="-1" aria-labelledby="editarPlanModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarPlan" action="" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="editarPlanModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>Editar Plan
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Nombre del Plan
                                </label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="precio" class="form-label">
                                    <i class="bi bi-currency-dollar me-1"></i>Precio
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="precio" class="form-control"
                                           step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="limite_usuarios" class="form-label">
                                    <i class="bi bi-people me-1"></i>Límite de Usuarios
                                </label>
                                <input type="number" name="limite_usuarios" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_restaurantes" class="form-label">
                                    <i class="bi bi-building me-1"></i>Límite de Restaurantes
                                </label>
                                <input type="number" name="limite_restaurantes" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="limite_sucursales" class="form-label">
                                    <i class="bi bi-shop me-1"></i>Límite de Sucursales
                                </label>
                                <input type="number" name="limite_sucursales" class="form-control" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Descripción
                            </label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="intervalo" class="form-label">
                                <i class="bi bi-clock me-1"></i>Intervalo
                            </label>
                            <select name="intervalo" class="form-select" required>
                                <option value="mes">Mensual</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info">
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Ingresa las características separadas por comas
                                </p>
                                <div class="form-floating">
                                    <textarea name="caracteristicas" class="form-control" rows="3"
                                              placeholder="Ejemplo: Gestión multi-sucursal, API personalizada, Reportes en tiempo real"
                                              required minlength="1" style="height: 100px"></textarea>
                                    <label>Características adicionales</label>
                                </div>
                                @error('caracteristicas')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="bi bi-check-circle me-1"></i>Guardar Cambios
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
