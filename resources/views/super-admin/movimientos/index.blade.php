@extends('layouts.app')

@section('title', 'Auditoría de Movimientos - FoodOps')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/movimientos.css') }}">
    <style>


        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            background-color: var(--primary-color) !important;
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000;
        }

        .badge.bg-info {
            background-color: var(--primary-light) !important;
            color: var(--primary-color) !important;
        }

        dt {
            font-weight: 500;
        }

        dd {
            margin-bottom: 0.5rem;
        }

        .modal.fade .modal-dialog {
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .spinner-border.text-primary {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Auditoría de Movimientos</h5>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2">Mostrar:</span>
                            {{-- Formulario para seleccionar cantidad de resultados --}}
                            <form id="perPageForm" action="{{ route('superadmin.movimientos') }}" method="GET"
                                  class="d-inline-block">
                                @foreach(request()->except(['por_pagina', '_token']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $item)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <select name="por_pagina" id="por_pagina" class="form-select form-select-sm"
                                        style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('por_pagina', 10) == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="50" {{ request('por_pagina') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('por_pagina') == 100 ? 'selected' : '' }}>100
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <form action="{{ route('superadmin.movimientos') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tipo" class="form-label">Tipo de Operación</label>
                                        <select name="tipo" id="tipo" class="form-select">
                                            <option value="">Todos</option>
                                            <option value="INSERT" {{ request('tipo') == 'INSERT' ? 'selected' : '' }}>
                                                INSERT
                                            </option>
                                            <option value="UPDATE" {{ request('tipo') == 'UPDATE' ? 'selected' : '' }}>
                                                UPDATE
                                            </option>
                                            <option value="DELETE" {{ request('tipo') == 'DELETE' ? 'selected' : '' }}>
                                                DELETE
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tabla_modificada" class="form-label">Tabla</label>
                                        <input type="text" name="tabla_modificada" id="tabla_modificada"
                                               class="form-control" value="{{ request('tabla_modificada') }}"
                                               placeholder="Nombre de la tabla">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fecha_inicio" class="form-label">Fecha/Hora Inicio</label>
                                        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio"
                                               class="form-control" value="{{ request('fecha_inicio') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="intervalo" class="form-label">Intervalo Rápido</label>
                                        <select name="intervalo" id="intervalo" class="form-select">
                                            <option value="">Personalizado</option>
                                            <option value="hoy" {{ request('intervalo') == 'hoy' ? 'selected' : '' }}>
                                                Hoy
                                            </option>
                                            <option value="ayer" {{ request('intervalo') == 'ayer' ? 'selected' : '' }}>
                                                Ayer
                                            </option>
                                            <option
                                                value="ultima_semana" {{ request('intervalo') == 'ultima_semana' ? 'selected' : '' }}>
                                                Última Semana
                                            </option>
                                            <option
                                                value="ultimo_mes" {{ request('intervalo') == 'ultimo_mes' ? 'selected' : '' }}>
                                                Último Mes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i> Filtrar
                                    </button>
                                    <a href="{{ route('superadmin.movimientos') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Resumen -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-info-circle me-2"></i>
                                    Mostrando {{ $movimientos->firstItem() ?? 0 }} - {{ $movimientos->lastItem() ?? 0 }}
                                    de {{ $movimientos->total() }} registros
                                </div>
                                <div>
                                    Página {{ $movimientos->currentPage() }} de {{ $movimientos->lastPage() }}
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Movimientos -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['ordenar_por' => 'created_at', 'orden' => request('orden') == 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Fecha
                                            @if(request('ordenar_por') == 'created_at')
                                                <i class="bi bi-arrow-{{ request('orden') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Tabla</th>
                                    <th>Valor Anterior</th>
                                    <th>Valor Actual</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($movimientos as $movimiento)
                                    <tr>
                                        <td>{{ $movimiento->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            @if($movimiento->usuario)
                                                <button class="btn btn-sm btn-link p-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#verUsuarioDetalle"
                                                        data-usuario-id="{{ $movimiento->usuario->id }}"
                                                        title="Ver detalles del usuario">
                                                    <i class="bi bi-person-circle fs-5"></i>
                                                </button>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                                <span
                                                    class="badge bg-{{ $movimiento->tipo == 'INSERT' ? 'success' : ($movimiento->tipo == 'UPDATE' ? 'warning' : 'danger') }}">
                                                    {{ $movimiento->tipo }}
                                                </span>
                                        </td>
                                        <td>{{ $movimiento->tabla_modificada }}</td>
                                        <td>
                                            <pre
                                                class="mb-0">{{ json_encode($movimiento->valor_anterior, JSON_PRETTY_PRINT) }}</pre>
                                        </td>
                                        <td>
                                            <pre
                                                class="mb-0">{{ json_encode($movimiento->valor_actual, JSON_PRETTY_PRINT) }}</pre>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="bi bi-search me-2"></i>
                                            No se encontraron movimientos
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted d-none d-md-block">
                                Mostrando {{ $movimientos->firstItem() ?? 0 }} - {{ $movimientos->lastItem() ?? 0 }}
                                de {{ $movimientos->total() }} registros
                            </div>
                            <div>
                                {{ $movimientos->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalles Usuario -->
    <div class="modal fade" id="verUsuarioDetalle" tabindex="-1" aria-labelledby="verUsuarioDetalleLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="verUsuarioDetalleLabel">
                        <i class="bi bi-person-circle me-2"></i>Detalles del Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Loading spinner -->
                    <div id="usuario-detail-loading" class="p-4">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando detalles del usuario...</p>
                        </div>
                    </div>

                    <!-- Contenido del usuario -->
                    <div id="usuario-detail-content" class="d-none">
                        <!-- Información Principal -->
                        <div class="bg-light p-4 border-bottom">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-circle">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-1" id="user-detail-nombre"></h4>
                                    <p class="text-muted mb-0" id="user-detail-email"></p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span id="user-detail-activo" class="badge"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles -->
                        <div class="p-4">
                            <div class="row g-4">
                                <!-- Información Personal -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-person-lines-fill text-primary me-2"></i>
                                                Información Personal
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 text-muted">Nombre:</dt>
                                                <dd class="col-sm-8" id="user-detail-nombre-completo"></dd>

                                                <dt class="col-sm-4 text-muted">Email:</dt>
                                                <dd class="col-sm-8" id="user-detail-email-detalle"></dd>

                                                <dt class="col-sm-4 text-muted">Celular:</dt>
                                                <dd class="col-sm-8" id="user-detail-celular"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del Tenant -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-building text-primary me-2"></i>
                                                Información del Tenant
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 text-muted">Tenant:</dt>
                                                <dd class="col-sm-8" id="user-detail-tenant"></dd>

                                                <dt class="col-sm-4 text-muted">Dominio:</dt>
                                                <dd class="col-sm-8" id="user-detail-tenant-dominio"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del Restaurante -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-shop text-primary me-2"></i>
                                                Información del Restaurante
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 text-muted">Restaurante:</dt>
                                                <dd class="col-sm-8" id="user-detail-restaurante"></dd>

                                                <dt class="col-sm-4 text-muted">RUC:</dt>
                                                <dd class="col-sm-8" id="user-detail-restaurante-ruc"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>

                                <!-- Roles y Permisos -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-shield-check text-primary me-2"></i>
                                                Roles y Permisos
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="user-detail-roles"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información de Auditoría -->
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h6 class="mb-0">
                                                <i class="bi bi-clock-history text-primary me-2"></i>
                                                Información de Auditoría
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-2 text-muted">Creado:</dt>
                                                <dd class="col-sm-4" id="user-detail-created-at"></dd>

                                                <dt class="col-sm-2 text-muted">Actualizado:</dt>
                                                <dd class="col-sm-4" id="user-detail-updated-at"></dd>

                                                <dt class="col-sm-2 text-muted">Último acceso:</dt>
                                                <dd class="col-sm-4" id="user-detail-ultimo-acceso"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje de error -->
                    <div id="usuario-detail-error" class="d-none p-4">
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span id="usuario-detail-error-message">Error al cargar los datos del usuario.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/super-admin/movimientos.js') }}"></script>
    @endpush
@endsection
