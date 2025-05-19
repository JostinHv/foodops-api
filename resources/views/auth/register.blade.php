@extends('layouts.app')

@section('title', 'Registro - FoodOps')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="container mt-3">
    <a href="{{ route('home') }}" class="text-muted fs-8 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver al Inicio
    </a>
</div>

<div class="register-container mx-auto mt-5 p-4 bg-white rounded shadow-sm">
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="50">
    </div>

    <h3 class="text-center">Registro</h3>
    <p class="text-muted text-center">Ingresa tus datos para registrarse</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control bg-light" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo</label>
            <input type="email" name="email" id="email" class="form-control bg-light" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control bg-light" required>
        </div>

        <button type="submit" class="btn btn-dark w-100">Registrarse</button>
    </form>

    <p class="text-center mt-3 text-muted">¿Tienes una cuenta? <a href="{{ route('login') }}" class="text-danger">Iniciar Sesión</a></p>
</div>
@endsection
