@extends('layouts.app')

@section('title', 'Gestión de Sugerencias')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sugerencias.css') }}">
<style>
    .gestion-sugerencias-card .card-header {
        background: var(--primary-color) !important;
        color: var(--text-light);
    }
    .sugerencias-dashboard {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .sugerencias-dashboard .kpi {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        padding: 1.1rem 2rem;
        min-width: 180px;
        text-align: center;
        flex: 1 1 180px;
    }
    .sugerencias-dashboard .kpi-title {
        font-size: 1.05em;
        color: var(--text-muted);
        margin-bottom: 0.2em;
    }
    .sugerencias-dashboard .kpi-value {
        font-size: 2.1em;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.1em;
    }
    .sugerencias-dashboard .kpi.badge-pendiente .kpi-value { color: var(--warning-color); }
    .sugerencias-dashboard .kpi.badge-revisada .kpi-value { color: var(--accent-color); }
    .sugerencias-dashboard .kpi.badge-resuelta .kpi-value { color: var(--success-color); }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="card gestion-sugerencias-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0" style="font-size:1.5rem;">Sugerencias de Usuarios</h2>
                        <p class="mb-0 text-light" style="font-size:1em;">Administra y responde las sugerencias enviadas por los usuarios</p>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Dashboard KPIs -->
                    @php
                        $total = $sugerencias->count();
                        $pendientes = $sugerencias->where('estado', 'pendiente')->count();
                        $revisadas = $sugerencias->where('estado', 'revisada')->count();
                        $resueltas = $sugerencias->where('estado', 'resuelta')->count();
                    @endphp
                    <div class="sugerencias-dashboard mb-4">
                        <div class="kpi">
                            <div class="kpi-title">Total</div>
                            <div class="kpi-value">{{ $total }}</div>
                        </div>
                        <div class="kpi badge-pendiente">
                            <div class="kpi-title">Pendientes</div>
                            <div class="kpi-value">{{ $pendientes }}</div>
                        </div>
                        <div class="kpi badge-revisada">
                            <div class="kpi-title">Revisadas</div>
                            <div class="kpi-value">{{ $revisadas }}</div>
                        </div>
                        <div class="kpi badge-resuelta">
                            <div class="kpi-title">Resueltas</div>
                            <div class="kpi-value">{{ $resueltas }}</div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Sugerencia</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sugerencias as $sugerencia)
                                    <tr>
                                        <td>{{ $sugerencia->id }}</td>
                                        <td>{{ $sugerencia->usuario->nombres ?? 'N/A' }} {{ $sugerencia->usuario->apellidos ?? '' }}</td>
                                        <td>{{ Str::limit($sugerencia->sugerencia, 60) }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($sugerencia->estado === 'pendiente') badge-pendiente 
                                                @elseif($sugerencia->estado === 'revisada') badge-revisada 
                                                @else badge-resuelta @endif">
                                                {{ ucfirst($sugerencia->estado) }}
                                            </span>
                                        </td>
                                        <td>{{ $sugerencia->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm ver-sugerencia-btn" data-id="{{ $sugerencia->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success btn-sm cambiar-estado-btn" data-id="{{ $sugerencia->id }}" data-estado="{{ $sugerencia->estado }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm eliminar-sugerencia-btn" data-id="{{ $sugerencia->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Sugerencia -->
<div class="modal fade" id="verSugerenciaModal" tabindex="-1" aria-labelledby="verSugerenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verSugerenciaModalLabel">Detalle de Sugerencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="detalleSugerencia"></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-labelledby="cambiarEstadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiarEstadoModalLabel">Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" id="sugerenciaIdEstado" name="sugerencia_id">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo estado</label>
                        <select class="form-select" id="nuevoEstado" name="estado">
                            <option value="pendiente">Pendiente</option>
                            <option value="revisada">Revisada</option>
                            <option value="resuelta">Resuelta</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarCambioEstado">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Sugerencia -->
<div class="modal fade" id="eliminarSugerenciaModal" tabindex="-1" aria-labelledby="eliminarSugerenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarSugerenciaModalLabel">Eliminar Sugerencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta sugerencia?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminarSugerencia">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/super-admin/sugerencias.js') }}"></script>
@endpush 