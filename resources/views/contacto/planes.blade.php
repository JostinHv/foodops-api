<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - FoodOps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/contacto-planes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="me-2" style="width: 32px; height: 32px;">
                <span class="fw-bold" style="color: var(--primary-color);">FoodOps</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#planes">Planes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('contacto.planes') }}">Contacto</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="mb-3" style="width: 64px; height: 64px;">
                            <h2 class="mb-2">Solicita Información</h2>
                            <p class="custom-text-muted">Complete el formulario para recibir más información sobre nuestros planes</p>
                        </div>

                        <form id="contactForm" class="needs-validation" novalidate>
                            <!-- Paso 1: Información Personal -->
                            <div id="step1" class="form-step">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        <div class="invalid-feedback">Por favor ingrese su nombre completo</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Por favor ingrese un correo electrónico válido</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono" required>
                                        <div class="invalid-feedback">Por favor ingrese un número de teléfono válido</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="empresa" class="form-label">Nombre de la Empresa</label>
                                        <input type="text" class="form-control" id="empresa" name="empresa">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary step-nav-btn" data-action="next">
                                        Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Paso 2: Selección de Plan -->
                            <div id="step2" class="form-step" style="display: none;">
                                <div class="mb-3">
                                    <label for="plan" class="form-label">Plan de Interés</label>
                                    <select class="form-select" id="plan" name="plan" required>
                                        <option value="">Seleccione un plan</option>
                                        @foreach($planes as $plan)
                                            <option value="{{ $plan->id }}" 
                                                data-nombre="{{ $plan->nombre }}"
                                                data-precio="{{ $plan->precio }}"
                                                data-intervalo="{{ $plan->intervalo }}"
                                                data-caracteristicas="{{ json_encode($plan->caracteristicas) }}">
                                                {{ $plan->nombre }} - S/. {{ $plan->precio }}/{{ $plan->intervalo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione un plan</div>
                                </div>

                                <div id="planPreview" class="plan-preview">
                                    <h4 class="plan-nombre mb-3"></h4>
                                    <div class="plan-price mb-3"></div>
                                    <div class="plan-caracteristicas"></div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary step-nav-btn" data-action="prev">
                                        <i class="bi bi-arrow-left me-2"></i>Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary step-nav-btn" data-action="next">
                                        Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Paso 3: Mensaje -->
                            <div id="step3" class="form-step" style="display: none;">
                                <div class="mb-3">
                                    <label for="mensaje" class="form-label">Mensaje Adicional</label>
                                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" 
                                        placeholder="Cuéntenos más sobre sus necesidades..."></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary step-nav-btn" data-action="prev">
                                        <i class="bi bi-arrow-left me-2"></i>Anterior
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        Enviar Solicitud <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-5" style="background-color: var(--dark-bg); color: var(--text-light);">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="me-2" style="height: 40px;">
                        <span class="h4 mb-0" style="color: var(--primary-color);">FoodOps</span>
                    </div>
                    <p class="custom-text-muted">Transformando la gestión de restaurantes con tecnología innovadora.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3" style="color: var(--primary-color);">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="custom-text-muted hover-primary">Inicio</a></li>
                        <li class="mb-2"><a href="{{ route('detalles.planes') }}" class="custom-text-muted hover-primary">Planes</a></li>
                        <li class="mb-2"><a href="{{ route('contacto') }}" class="custom-text-muted hover-primary">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3" style="color: var(--primary-color);">Soporte</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="custom-text-muted hover-primary">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="custom-text-muted hover-primary">Centro de Ayuda</a></li>
                        <li class="mb-2"><a href="#" class="custom-text-muted hover-primary">Soporte Técnico</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2" style="color: var(--primary-color);"></i>
                            <a href="mailto:info@foodops.com" class="custom-text-muted hover-primary">info@foodops.com</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2" style="color: var(--primary-color);"></i>
                            <a href="tel:+1234567890" class="custom-text-muted hover-primary">+1 (234) 567-890</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.1);">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="custom-text-muted mb-0">&copy; {{ date('Y') }} FoodOps. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="{{ route('terminos.condiciones') }}" class="custom-text-muted hover-primary me-3">Términos y
                        Condiciones</a>
                    <a href="{{ route('politica.privacidad') }}" class="custom-text-muted hover-primary">Política de
                        Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/contacto-planes.js') }}"></script>
</body>
</html> 