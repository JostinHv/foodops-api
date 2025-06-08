@extends('layouts.app')

@section('title', 'Configuración de IGV')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/super-admin/igv.css') }}">
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: var(--card-shadow);
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .status-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1;
        }

        .igv-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .card:hover .igv-icon {
            color: var(--primary-color);
        }

        .action-buttons {
            opacity: 0;
            transition: opacity 0.2s;
        }

        .card:hover .action-buttons {
            opacity: 1;
        }

        .section-title {
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .active-igv {
            border-left: 4px solid var(--accent-color);
        }

        .inactive-igv {
            border-left: 4px solid var(--danger-color);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Configuración de IGV</h1>
                <p class="text-muted">Gestiona las tasas de IGV por año</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoIgvModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Tasa IGV
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- IGV Activo -->
            <div class="col-md-12 mb-4">
                <h4 class="section-title">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    IGV Activo
                </h4>
                @php
                    $igvActivo = $igvs->where('activo', true)->first();
                @endphp
                @if($igvActivo)
                    <div class="card active-igv">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-percent igv-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">IGV {{ $igvActivo->anio }}</h5>
                                            <p class="text-muted mb-0">
                                                Valor: {{ $igvActivo->valor_porcentaje }}% 
                                                (Decimal: {{ $igvActivo->valor_decimal }})
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-sm btn-outline-primary me-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarIgvModal"
                                            data-id="{{ $igvActivo->id }}"
                                            data-anio="{{ $igvActivo->anio }}"
                                            data-valor="{{ $igvActivo->valor_porcentaje }}">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <form action="{{ route('superadmin.igv.toggle-activo', $igvActivo->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-power"></i> Desactivar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No hay IGV activo. Por favor, active uno de los IGV disponibles o cree uno nuevo.
                    </div>
                @endif
            </div>

            <!-- IGV Inactivos -->
            <div class="col-md-12">
                <h4 class="section-title">
                    <i class="bi bi-x-circle-fill text-danger me-2"></i>
                    IGV Inactivos
                </h4>
                <div class="row g-4">
                    @forelse($igvs->where('activo', false) as $igv)
                        <div class="col-md-6 col-lg-4">
                            <div class="card inactive-igv h-100">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="bi bi-percent igv-icon"></i>
                                        <h5 class="card-title mb-1">IGV {{ $igv->anio }}</h5>
                                        <p class="text-muted small mb-0">
                                            Valor: {{ $igv->valor_porcentaje }}% 
                                            (Decimal: {{ $igv->valor_decimal }})
                                        </p>
                                    </div>

                                    <div class="action-buttons d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editarIgvModal"
                                                data-id="{{ $igv->id }}"
                                                data-anio="{{ $igv->anio }}"
                                                data-valor="{{ $igv->valor_porcentaje }}">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <form action="{{ route('superadmin.igv.toggle-activo', $igv->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-power"></i> Activar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                No hay IGV inactivos disponibles.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nuevo IGV -->
    <div class="modal fade" id="nuevoIgvModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('superadmin.igv.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nueva Tasa IGV
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" 
                                   class="form-control @error('anio') is-invalid @enderror" 
                                   id="anio" 
                                   name="anio" 
                                   value="{{ date('Y') }}"
                                   min="2000" 
                                   max="2100"
                                   required>
                            @error('anio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="valor_porcentaje" class="form-label">Valor Porcentual</label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('valor_porcentaje') is-invalid @enderror" 
                                       id="valor_porcentaje" 
                                       name="valor_porcentaje" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('valor_porcentaje')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Crear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar IGV -->
    <div class="modal fade" id="editarIgvModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarIgv" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2"></i>
                            Editar Tasa IGV
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editar_anio" class="form-label">Año</label>
                            <input type="number" 
                                   class="form-control @error('anio') is-invalid @enderror" 
                                   id="editar_anio" 
                                   name="anio" 
                                   min="2000" 
                                   max="2100"
                                   required>
                            @error('anio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="editar_valor_porcentaje" class="form-label">Valor Porcentual</label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('valor_porcentaje') is-invalid @enderror" 
                                       id="editar_valor_porcentaje" 
                                       name="valor_porcentaje" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('valor_porcentaje')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/super-admin/igv.js') }}"></script>
@endpush
