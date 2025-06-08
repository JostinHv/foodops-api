@extends('layouts.app')

@section('title', 'Métodos de Pago')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/super-admin/pago.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Métodos de Pago</h1>
                <p class="text-muted">Gestiona los métodos de pago disponibles en el sistema</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoMetodoPagoModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Método de Pago
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse($metodosPago as $metodo)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <span class="badge {{ $metodo->activo ? 'bg-success' : 'bg-danger' }} status-badge">
                                <i class="bi {{ $metodo->activo ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                {{ $metodo->activo ? 'Activo' : 'Inactivo' }}
                            </span>

                            <div class="text-center mb-3">
                                <i class="bi bi-credit-card method-icon"></i>
                                <h5 class="card-title mb-1">{{ $metodo->nombre }}</h5>
                                @if($metodo->descripcion)
                                    <p class="text-muted small mb-0">{{ $metodo->descripcion }}</p>
                                @endif
                            </div>

                            <div class="action-buttons d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editarMetodoPagoModal"
                                        data-id="{{ $metodo->id }}"
                                        data-nombre="{{ $metodo->nombre }}"
                                        data-descripcion="{{ $metodo->descripcion }}">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <form action="{{ route('superadmin.pago.toggle-activo', $metodo->id) }}" method="POST"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $metodo->activo ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        <i class="bi bi-power"></i> {{ $metodo->activo ? 'Desactivar' : 'Activar' }}
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
                        No hay métodos de pago registrados. ¡Crea uno nuevo!
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal: Nuevo Método de Pago -->
    <div class="modal fade" id="nuevoMetodoPagoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('superadmin.pago.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nuevo Método de Pago
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <i class="bi bi-credit-card input-icon"></i>
                            <input type="text"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   id="nombre"
                                   name="nombre"
                                   placeholder="Nombre del método de pago"
                                   required>
                            <label for="nombre">Nombre</label>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <i class="bi bi-card-text input-icon"></i>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                      id="descripcion"
                                      name="descripcion"
                                      placeholder="Descripción del método de pago"
                                      style="height: 100px"></textarea>
                            <label for="descripcion">Descripción</label>
                            @error('descripcion')
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

    <!-- Modal: Editar Método de Pago -->
    <div class="modal fade" id="editarMetodoPagoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarMetodoPago" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2"></i>
                            Editar Método de Pago
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <i class="bi bi-credit-card input-icon"></i>
                            <input type="text"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   id="editar_nombre"
                                   name="nombre"
                                   placeholder="Nombre del método de pago"
                                   required>
                            <label for="editar_nombre">Nombre</label>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <i class="bi bi-card-text input-icon"></i>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                      id="editar_descripcion"
                                      name="descripcion"
                                      placeholder="Descripción del método de pago"
                                      style="height: 100px"></textarea>
                            <label for="editar_descripcion">Descripción</label>
                            @error('descripcion')
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
    <script src="{{ asset('js/metodo-pago.js') }}"></script>
@endpush
