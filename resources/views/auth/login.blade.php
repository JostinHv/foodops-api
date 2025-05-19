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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <input type="email" id="email" name="email" class="form-control bg-light" required autofocus>
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
@endsection
