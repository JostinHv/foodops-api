@extends('layouts.app')

@section('title', 'Detalles de Mesa')

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Detalles de Mesa</h1>
            <p class="mb-0 text-muted">Información detallada de la mesa</p>
        </div>
        <div>
            <a href="{{ route('gerente.mesas') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a Mesas
            </a>
            <a href="{{ route('gerente.mesas.edit', $mesa->id) }}" class="btn btn-dark ms-2">
                <i class="bi bi-pencil me-2"></i>Editar Mesa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nombre</label>
                            <p class="h5">{{ $mesa->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Capacidad</label>
                            <p class="h5">{{ $mesa->capacidad }} personas</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Estado</label>
                            <p>
                                @php
                                    $badgeClass = match($mesa->estadoMesa->nombre) {
                                        'Libre' => 'bg-success',
                                        'Ocupada' => 'bg-warning',
                                        'Reservada' => 'bg-info',
                                        'Sucia' => 'bg-danger',
                                        'En Limpieza' => 'bg-secondary',
                                        'Bloqueada' => 'bg-dark',
                                        default => 'bg-primary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $mesa->estadoMesa->nombre }}</span>
                            </p>
                        </div>
                        @if($mesa->descripcion)
                        <div class="col-12">
                            <label class="form-label text-muted">Descripción</label>
                            <p>{{ $mesa->descripcion }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cambiar Estado</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @foreach($estadosMesa as $estado)
                            @php
                                $buttonClass = match($estado->nombre) {
                                    'Libre' => 'btn-success',
                                    'Ocupada' => 'btn-warning',
                                    'Reservada' => 'btn-info',
                                    'Sucia' => 'btn-danger',
                                    'En Limpieza' => 'btn-secondary',
                                    'Bloqueada' => 'btn-dark',
                                    default => 'btn-primary'
                                };

                                $icon = match($estado->nombre) {
                                    'Libre' => 'bi-check-circle',
                                    'Ocupada' => 'bi-person-fill',
                                    'Reservada' => 'bi-calendar-check',
                                    'Sucia' => 'bi-exclamation-triangle',
                                    'En Limpieza' => 'bi-droplet',
                                    'Bloqueada' => 'bi-lock-fill',
                                    default => 'bi-arrow-right'
                                };
                            @endphp

                            <form action="{{ route('gerente.mesas.cambiar-estado', $mesa->id) }}"
                                method="POST"
                                class="d-grid">
                                @csrf
                                <input type="hidden" name="estado_mesa_id" value="{{ $estado->id }}">
                                <button type="submit"
                                    class="btn {{ $buttonClass }} {{ $mesa->estadoMesa->id === $estado->id ? 'active' : '' }}"
                                    {{ $mesa->estadoMesa->id === $estado->id ? 'disabled' : '' }}>
                                    <i class="bi {{ $icon }} me-2"></i>
                                    Marcar como {{ $estado->nombre }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cerrar alertas automáticamente
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    });
});
</script>
@endpush

@endsection
