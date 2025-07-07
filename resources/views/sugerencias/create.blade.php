@extends('layouts.app')

@section('title', 'Enviar Sugerencia')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sugerencias.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card sugerencia-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Enviar una sugerencia</h4>
                    <a href="{{ route('sugerencias.historial') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-clock-history me-1"></i> Mi historial
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sugerencias.store') }}" id="formSugerencia">
                        @csrf
                        <div class="mb-3">
                            <label for="sugerencia" class="form-label">Tu sugerencia</label>
                            <textarea class="form-control @error('sugerencia') is-invalid @enderror" id="sugerencia" name="sugerencia" rows="5" required placeholder="¿Cómo podemos mejorar tu experiencia? (máx. 300 palabras)">{{ old('sugerencia') }}</textarea>
                            <div class="form-text"><span id="contadorPalabras">0</span>/300 palabras</div>
                            @error('sugerencia')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary px-4" id="btnEnviar">
                                <span id="btnEnviarText">Enviar</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btnLimpiar">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Toast de éxito -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastSugerencia" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-show="{{ session('success') ? '1' : '0' }}">
        <div class="d-flex">
            <div class="toast-body">
                ¡Sugerencia enviada exitosamente!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/sugerencias.js') }}"></script>
@endpush 