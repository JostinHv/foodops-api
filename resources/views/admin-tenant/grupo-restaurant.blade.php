@extends('layouts.app')

@section('title', 'Grupos de Restaurantes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-tenant/grupo-restaurant.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Grupos de Restaurantes</h1>
                <p class="mb-0 text-muted">Gestiona los grupos de restaurantes de tu organización</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoGrupoModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Grupo
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Grupos</div>
                        <h3 class="mb-1">{{ $grupos?->count() ?? 0 }}</h3>
                        <small class="text-muted">Grupos activos</small>
                    </div>
                </div>
            </div>
            <!-- Nuevas tarjetas -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Restaurantes Asociados</div>
                        <h3 class="mb-1">{{ $grupos?->sum(fn($grupo) => $grupo->restaurantes?->count() ?? 0) ?? 0 }}</h3>
                        <small class="text-primary">Total en grupos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Grupos con Restaurantes</div>
                        <h3 class="mb-1">{{ $grupos?->filter(fn($grupo) => $grupo->restaurantes->count() > 0)->count() ?? 0 }}</h3>
                        <small class="text-success">Grupos en uso</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de grupos -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Grupos</h5>
                <span class="badge bg-primary">{{ $grupos?->count() ?? 0}} grupos encontrados</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Descripción</th>
                            <th>Estadísticas</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($grupos ?? [] as $grupo)
                            <tr>
                                <td>
                                    <strong>{{ $grupo->nombre }}</strong>
                                    <div><small
                                            class="text-muted">Creado: {{ $grupo->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </td>
                                <td>
                                    {{ $grupo->descripcion ?? 'Sin descripción' }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge bg-info">
                                            <i class="bi bi-building me-1"></i>{{ $grupo->restaurantes->count() }} restaurantes
                                        </span>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>{{ $grupo->restaurantes->where('activo', true)->count() }} activos
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles"
                                                data-bs-toggle="modal" data-bs-target="#verGrupoModal"
                                                data-grupo="{{ $grupo->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarGrupoModal"
                                                data-grupo="{{ $grupo->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay grupos registrados</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Crear Nuevo Grupo -->
    <div class="modal fade" id="nuevoGrupoModal" tabindex="-1" aria-labelledby="nuevoGrupoModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tenant.grupo-restaurant.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoGrupoModalLabel">Crear Nuevo Grupo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Grupo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">Crear Grupo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Grupo -->
    <div class="modal fade" id="editarGrupoModal" tabindex="-1" aria-labelledby="editarGrupoModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarGrupo" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarGrupoModalLabel">Editar Grupo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Grupo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Ver Detalles Grupo -->
    <div class="modal fade" id="verGrupoModal" tabindex="-1" aria-labelledby="verGrupoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verGrupoModalLabel">Detalles del Grupo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Nombre del Grupo</h6>
                        <p id="grupo-nombre" class="mb-3"></p>

                        <h6>Descripción</h6>
                        <p id="grupo-descripcion" class="mb-3"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-tenant/grupo-restaurant.js') }}"></script>
@endpush
