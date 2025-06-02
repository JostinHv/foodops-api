@extends('layouts.app')

@section('title', 'Iniciar Sesión - FoodOps')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container mt-3">
        <a href="{{ route('home') }}" class="text-muted fs-8 text-decoration-none">
            <i class="bi bi-arrow-left"></i> Volver al Inicio
        </a>
    </div>

    <div class="login-container">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mb-2" width="40">
        </div>

        <h2>Iniciar Sesión</h2>
        <p class="text-muted text-center mb-4">Ingresa tus credenciales para acceder al sistema</p>

        @error('credentials')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @enderror

        <form method="POST" action="{{ route('login-submit') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <input type="email"
                       name="email"
                       id="email"
                       title="Por favor ingrese un correo electrónico válido"
                       class="form-control bg-light @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required>
                <span id="email-feedback" class="text-sm"></span>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Contraseña</label>
                </div>
                <input type="password" id="password" name="password" class="form-control bg-light" required>
            </div>

            <button type="submit" class="btn btn-login">Ingresar</button>
        </form>

        <p class="text-center mt-3 text-muted text-link">
            ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-danger">Regístrate</a>
        </p>
    </div>
    @push('scripts')
        <script type="module" src="{{ asset('js/auth/login.js') }}"></script>
    @endpush
@endsection
