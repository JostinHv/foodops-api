@extends('layouts.app')

@section('title', 'Cierre de Caja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cajero/caja.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-left me-2"></i>Cierre de Caja</h5>
                </div>
                <div class="card-body">
                    @if(isset($montoFinalEsperado))
                    <div class="alert alert-info">
                        <strong>Monto Final Esperado:</strong> S/. {{ number_format($montoFinalEsperado, 2) }}
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('cajero.caja.cierre.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="monto_efectivo_contado" class="form-label">Efectivo contado *</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('monto_efectivo_contado') is-invalid @enderror" 
                                       id="monto_efectivo_contado" name="monto_efectivo_contado" required 
                                       value="{{ old('monto_efectivo_contado') }}">
                                @error('monto_efectivo_contado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="monto_tarjetas" class="form-label">Tarjetas</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('monto_tarjetas') is-invalid @enderror" 
                                       id="monto_tarjetas" name="monto_tarjetas" value="{{ old('monto_tarjetas', 0) }}">
                                @error('monto_tarjetas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="monto_transferencias" class="form-label">Transferencias</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('monto_transferencias') is-invalid @enderror" 
                                       id="monto_transferencias" name="monto_transferencias" value="{{ old('monto_transferencias', 0) }}">
                                @error('monto_transferencias')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="monto_otros" class="form-label">Otros</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('monto_otros') is-invalid @enderror" 
                                   id="monto_otros" name="monto_otros" value="{{ old('monto_otros', 0) }}">
                            @error('monto_otros')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                      id="observaciones" name="observaciones" rows="2">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-check-circle me-2"></i>Cerrar Caja
                        </button>
                        <a href="{{ route('cajero.caja') }}" class="btn btn-link w-100 mt-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 