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
                        <small class="text-muted">{{ $mesas->where('estado', 'Ocupada')->count() }} de {{ $totalMesas }} mesas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Asientos Disponibles</div>
                        <h3 class="mb-1">{{ $totalAsientos - ($mesas->where('estado', 'Ocupada')->sum('capacidad')) }}</h3>
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
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mesas as $mesa)
                                <tr>
                                    <td><strong>{{ $mesa['nombre'] }}</strong></td>
                                    <td>{{ $mesa['capacidad'] }} personas</td>
                                    <td>
                                        @php
                                            $badgeClass = match($mesa['estado']) {
                                                'Libre' => 'bg-success',
                                                'Ocupada' => 'bg-warning text-dark',
                                                'Reservada' => 'bg-info',
                                                'Sucia' => 'bg-danger',
                                                'En Limpieza' => 'bg-secondary',
                                                'Bloqueada' => 'bg-dark',
                                                default => 'bg-primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $mesa['estado'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('gerente.mesas.show', $mesa['id']) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('gerente.mesas.edit', $mesa['id']) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Editar mesa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('gerente.mesas.destroy', $mesa['id']) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Está seguro de que desea eliminar esta mesa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar mesa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
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
        <div class="modal fade" id="nuevaMesaModal" tabindex="-1" aria-labelledby="nuevaMesaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formNuevaMesa" action="{{ route('gerente.mesas.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="nuevaMesaModalLabel">Nueva Mesa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text"
                                            class="form-control @error('nombre') is-invalid @enderror"
                                            id="nombre"
                                            name="nombre"
                                            placeholder="Ej: Mesa VIP 1"
                                            value="{{ old('nombre') }}"
                                            required>
                                    </div>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="capacidad" class="form-label">Capacidad de Personas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                                        <input type="number"
                                            class="form-control @error('capacidad') is-invalid @enderror"
                                            id="capacidad"
                                            name="capacidad"
                                            min="1"
                                            max="20"
                                            placeholder="Ej: 4"
                                            value="{{ old('capacidad') }}"
                                            required>
                                        <span class="input-group-text">personas</span>
                                    </div>
                                    @error('capacidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                        id="descripcion"
                                        name="descripcion"
                                        rows="2"
                                        placeholder="Ej: Mesa junto a la ventana, ideal para familias">{{ old('descripcion') }}</textarea>
                                </div>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
    </div>

    @push('scripts')
    <script src="{{ asset('js/gerente-sucursal/mesas.js') }}"></script>
    @endpush
@endsection
