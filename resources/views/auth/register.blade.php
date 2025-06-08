@extends('layouts.app')

@section('title', 'Registro - FoodOps')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@push('meta')
    <meta name="check-email-url" content="{{ route('check.email') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="container mt-3">
        <a href="{{ route('home') }}" class="fs-8 text-decoration-none text-light">
            <i class="bi bi-arrow-left"></i> Volver al Inicio
        </a>
    </div>

    <div class="register-container">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mb-2" width="40">
        </div>

        <h3>Registro</h3>
        <p class="text-muted text-center mb-4">Ingresa tus datos para registrarte</p>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register-submit') }}" id="registerForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombres" class="form-label">Nombres *</label>
                    <input type="text" name="nombres" id="nombres"
                           class="form-control bg-light @error('nombres') is-invalid @enderror"
                           value="{{ old('nombres') }}" required>
                    @error('nombres')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="apellidos" class="form-label">Apellidos *</label>
                    <input type="text" name="apellidos" id="apellidos"
                           class="form-control bg-light @error('apellidos') is-invalid @enderror"
                           value="{{ old('apellidos') }}" required>
                    @error('apellidos')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo *</label>
                <input type="email" name="email" id="email"
                       pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                       class="form-control bg-light @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                <span id="email-feedback" class="text-sm"></span>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nro_celular" class="form-label">Nro Celular (Opcional)</label>
                <input type="tel" name="nro_celular" id="nro_celular"
                       class="form-control bg-light @error('nro_celular') is-invalid @enderror"
                       value="{{ old('nro_celular') }}">
                @error('nro_celular')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña *</label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                           class="form-control bg-light @error('password') is-invalid @enderror" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña *</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control bg-light" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                        <i class="bi bi-eye" id="toggleConfirmIcon"></i>
                    </button>
                </div>
                <div id="passwordMatchFeedback" class="invalid-feedback" style="display: none;">
                    Las contraseñas no coinciden
                </div>
            </div>

            <button type="submit" class="btn btn-dark" id="submitBtn">Registrarse</button>
        </form>

        <p class="text-center mt-3 text-muted text-link">
            ¿Tienes una cuenta? <a href="{{ route('login') }}" class="text-danger">Iniciar Sesión</a>
        </p>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('password_confirmation');
                const togglePassword = document.getElementById('togglePassword');
                const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
                const passwordMatchFeedback = document.getElementById('passwordMatchFeedback');
                const submitBtn = document.getElementById('submitBtn');
                const form = document.getElementById('registerForm');

                function togglePasswordVisibility(input, icon) {
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                }

                function validatePasswords() {
                    const password = passwordInput.value;
                    const confirmPassword = confirmPasswordInput.value;

                    if (password && confirmPassword) {
                        const match = password === confirmPassword;
                        confirmPasswordInput.classList.toggle('is-invalid', !match);
                        passwordMatchFeedback.style.display = match ? 'none' : 'block';
                        return match;
                    }
                    confirmPasswordInput.classList.remove('is-invalid');
                    passwordMatchFeedback.style.display = 'none';
                    return true;
                }

                function validateForm() {
                    const validPasswords = validatePasswords();
                    const allFieldsFilled = form.checkValidity();
                    submitBtn.disabled = !(validPasswords && allFieldsFilled);
                    return validPasswords && allFieldsFilled;
                }

                togglePassword?.addEventListener('click', (e) => {
                    e.preventDefault();
                    togglePasswordVisibility(passwordInput, toggleIcon);
                });

                toggleConfirmPassword?.addEventListener('click', (e) => {
                    e.preventDefault();
                    togglePasswordVisibility(confirmPasswordInput, toggleConfirmIcon);
                });

                passwordInput?.addEventListener('input', validateForm);
                confirmPasswordInput?.addEventListener('input', validateForm);
                form?.addEventListener('input', validateForm);

                form?.addEventListener('submit', function (e) {
                    if (!validateForm()) {
                        e.preventDefault();
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                <i class="bi bi-exclamation-circle me-2"></i>
                Por favor, corrige los errores antes de continuar.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
                        form.parentNode.insertBefore(alertDiv, form);
                        setTimeout(() => alertDiv.remove(), 5000);
                    }
                });

                validateForm();
            });
        </script>
        {{-- Importar el validador de email --}}
        <script type="module" src="{{ asset('js/auth/register.js') }}"></script>
    @endpush

@endsection
