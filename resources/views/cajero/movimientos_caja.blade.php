@extends('layouts.app')

@section('title', 'Movimientos de Caja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cajero/caja.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Movimientos de Caja</h2>
            <p class="text-muted mb-0">Listado de todos los movimientos de la caja</p>
        </div>
        <a href="{{ route('cajero.caja') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver a Caja
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Usuario</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $mov)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $mov->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $mov->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $mov->tipo_movimiento_caja_id == 1 ? 'success' : ($mov->tipo_movimiento_caja_id == 2 ? 'warning' : ($mov->tipo_movimiento_caja_id == 3 ? 'info' : 'danger')) }}">
                                        {{ $mov->tipoMovimientoCaja->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $mov->metodoPago->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-bold text-{{ $mov->tipo_movimiento_caja_id == 1 || $mov->tipo_movimiento_caja_id == 3 ? 'success' : 'danger' }}">
                                    {{ $mov->tipo_movimiento_caja_id == 1 || $mov->tipo_movimiento_caja_id == 3 ? '+' : '-' }}S/. {{ number_format($mov->monto, 2) }}
                                </td>
                                <td>{{ $mov->usuario->nombres ?? 'N/A' }}</td>
                                <td>
                                    <small class="text-muted">{{ $mov->descripcion ?? 'Sin descripción' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h5>No hay movimientos registrados</h5>
                                    <p class="mb-0">Los movimientos aparecerán aquí cuando se realicen operaciones en la caja.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($movimientos->count() > 0)
                <div class="mt-3 text-muted">
                    <small>Total de movimientos: {{ $movimientos->count() }}</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 