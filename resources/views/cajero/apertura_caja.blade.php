@extends('layouts.app')

@section('title', 'Apertura de Caja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cajero/caja.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-in-right me-2"></i>Apertura de Caja</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cajero.caja.apertura.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="monto_inicial" class="form-label">Monto Inicial *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('monto_inicial') is-invalid @enderror" 
                                   id="monto_inicial" name="monto_inicial" required autofocus 
                                   value="{{ old('monto_inicial') }}">
                            @error('monto_inicial')
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
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i>Abrir Caja
                        </button>
                        <a href="{{ route('cajero.caja') }}" class="btn btn-link w-100 mt-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 