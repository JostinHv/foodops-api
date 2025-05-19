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

        <form method="POST" action="{{ route('register-submit') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombres" class="form-label">Nombres *</label>
                    <input type="text" name="nombres" id="nombres" class="form-control bg-light" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="apellidos" class="form-label">Apellidos *</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control bg-light" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo *</label>
                <input type="email" name="email" id="email" class="form-control bg-light" required>
            </div>

            <div class="mb-3">
                <label for="nro_celular" class="form-label">Nro Celular (Opcional)</label>
                <input type="tel" name="nro_celular" id="nro_celular" class="form-control bg-light">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña *</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control bg-light" required>
                    <span class="input-group-text" style="cursor: pointer;" id="togglePassword">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña *</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light" required>
                    <span class="input-group-text" style="cursor: pointer;" id="toggleConfirmPassword">
                        <i class="bi bi-eye" id="toggleConfirmIcon"></i>
                    </span>
                </div>
                <div id="passwordMatchFeedback" class="invalid-feedback" style="display: none;">
                    Las contraseñas no coinciden
                </div>
            </div>

            <button type="submit" class="btn btn-dark w-100">Registrarse</button>
        </form>

        <p class="text-center mt-3 text-muted">¿Tienes una cuenta? <a href="{{ route('login') }}" class="text-danger">Iniciar Sesión</a></p>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('password_confirmation');
                const togglePassword = document.getElementById('togglePassword');
                const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
                const passwordFeedback = document.getElementById('passwordFeedback');
                const passwordMatchFeedback = document.getElementById('passwordMatchFeedback');

                function togglePasswordVisibility(input, icon) {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);

                    if (type === 'text') {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }

                function validatePasswords() {
                    const password = passwordInput.value;
                    const confirmPassword = confirmPasswordInput.value;


                    // Validar que las contraseñas coincidan
                    if (confirmPassword && password !== confirmPassword) {
                        confirmPasswordInput.classList.add('is-invalid');
                        passwordMatchFeedback.style.display = 'block';
                    } else {
                        confirmPasswordInput.classList.remove('is-invalid');
                        passwordMatchFeedback.style.display = 'none';
                    }
                }

                // Event listeners para mostrar/ocultar contraseña
                togglePassword.addEventListener('click', () => {
                    togglePasswordVisibility(passwordInput, toggleIcon);
                });

                toggleConfirmPassword.addEventListener('click', () => {
                    togglePasswordVisibility(confirmPasswordInput, toggleConfirmIcon);
                });

                // Event listeners para validación en tiempo real
                passwordInput.addEventListener('input', validatePasswords);
                confirmPasswordInput.addEventListener('input', validatePasswords);

                // Validar el formulario antes de enviar
                document.querySelector('form').addEventListener('submit', function(e) {
                    validatePasswords();
                    if (document.querySelectorAll('.is-invalid').length > 0) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
