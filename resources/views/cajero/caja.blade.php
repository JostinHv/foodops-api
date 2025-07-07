@extends('layouts.app')

@section('title', 'Caja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cajero/caja.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Caja</h2>
                <p class="text-muted mb-0">Gestión de apertura y cierre de caja</p>
            </div>
            <div>
                @if($cajaAbierta)
                    <a href="{{ route('cajero.caja.cierre') }}" class="btn btn-danger">
                        <i class="bi bi-box-arrow-left me-2"></i>Cerrar Caja
                    </a>
                @else
                    <a href="{{ route('cajero.caja.apertura') }}" class="btn btn-success">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Abrir Caja
            </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
            <div class="card-body">
                        <h5 class="card-title mb-3">Estado de la Caja</h5>
                        <span class="badge bg-{{ $cajaAbierta ? 'success' : 'secondary' }} fs-6">
                            {{ $cajaAbierta ? 'ABIERTA' : 'CERRADA' }}
                        </span>

                        @if($cajaAbierta && $caja)
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2">
                                    <strong>Monto Inicial:</strong>
                                    <span class="text-success">S/. {{ number_format($caja->monto_inicial, 2) }}</span>
                                </li>
{{--                                <li class="mb-2">--}}
{{--                                    <strong>Monto Actual:</strong>--}}
{{--                                    <span class="text-primary">S/. {{ number_format($caja->monto_final_esperado ?? 0, 2) }}</span>--}}
{{--                                </li>--}}
                                <li class="mb-2">
                                    <strong>Usuario:</strong>
                                    <span>{{ $caja->usuario->nombres ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Fecha Apertura:</strong>
                                    <span>{{ $caja->fecha_apertura ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Hora Apertura:</strong>
                                    <span>{{ $caja->hora_apertura ?? 'N/A' }}</span>
                                </li>
                            </ul>
                        @else
                            <div class="text-muted mt-3">
                                <p class="mb-0">No hay caja abierta actualmente.</p>
                                <p class="mb-0">Haz clic en "Abrir Caja" para comenzar.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Movimientos Recientes</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Método</th>
                                        <th>Monto</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($movimientos as $mov)
                                        <tr>
                                            <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $mov->tipo_movimiento_caja_id == 1 ? 'success' : ($mov->tipo_movimiento_caja_id == 2 ? 'warning' : ($mov->tipo_movimiento_caja_id == 3 ? 'info' : 'danger')) }}">
                                                    {{ $mov->tipoMovimientoCaja->nombre ?? 'N/A' }}
                            </span>
                                            </td>
                                            <td>{{ $mov->metodoPago->nombre ?? 'N/A' }}</td>
                                            <td class="text-{{ $mov->tipo_movimiento_caja_id == 1 || $mov->tipo_movimiento_caja_id == 3 ? 'success' : 'danger' }}">
                                                {{ $mov->tipo_movimiento_caja_id == 1 || $mov->tipo_movimiento_caja_id == 3 ? '+' : '-' }}S/. {{ number_format($mov->monto, 2) }}
                                            </td>
                                            <td>{{ $mov->usuario->nombres ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No hay movimientos recientes.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($cajaAbierta)
                            <a href="{{ route('cajero.caja.movimientos') }}" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-list-ul me-1"></i>Ver todos los movimientos
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
