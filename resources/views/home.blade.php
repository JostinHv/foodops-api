<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodOps - Gestión Integral para Restaurantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
</head>
<body>
<!-- Header -->
<header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="me-2"
                 style="width: 32px; height: 32px;">
            <span class="fw-bold text-danger">FoodOps</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#planes">Planes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacto.planes') }}">Contacto</a>
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

<main>
    <section class="text-center py-5 section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Optimiza la Gestión de Tu Restaurante</h1>
            <p class="lead mb-4">
                FoodOps: La plataforma integral que digitaliza y simplifica la administración de tu negocio
                gastronómico, desde pedidos hasta facturación.
            </p>
            <a href="{{ url('/contacto-planes') }}" class="hero-button-primary">¡Quiero Optimizar Mi Gestión!</a>
        </div>
    </section>

    <section class="benefits-section text-center">
        <div class="container">
            <h2 class="mb-5">¿Tu Restaurante aún no pasa al siguiente nivel?</h2>
            <div class="row justify-content-center">
                <div class="col-md-4 benefit-item">
                    <i class="bi bi-speedometer2"></i>
                    <h5>Incrementa la Eficiencia</h5>
                    <p>Reduce tiempos en la toma de pedidos, gestión de mesas y facturación. Elimina cuellos de botella
                        y atiende más rápido.</p>
                </div>
                <div class="col-md-4 benefit-item">
                    <i class="bi bi-patch-check"></i>
                    <h5>Minimiza Errores</h5>
                    <p>Digitaliza tus procesos para evitar errores comunes en órdenes y cálculos. Asegura precisión en
                        cada operación.</p>
                </div>
                <div class="col-md-4 benefit-item">
                    <i class="bi bi-graph-up"></i>
                    <h5>Control Total y Crecimiento</h5>
                    <p>Obtén visibilidad completa de tus ventas, personal y finanzas. Identifica áreas de mejora y
                        potencializa tu crecimiento.</p>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-4 benefit-item">
                    <i class="bi bi-person-video2"></i>
                    <h5>Gestión de Personal Simplificada</h5>
                    <p>Administra usuarios y roles (meseros, gerentes, etc.) eficazmente, asigna permisos y supervisa su
                        actividad.</p>
                </div>
                <div class="col-md-4 benefit-item">
                    <i class="bi bi-building"></i>
                    <h5>Manejo Multi-Sucursal (Para Cadenas)</h5>
                    <p>Centraliza la gestión de múltiples restaurantes o sucursales desde una única plataforma,
                        estandarizando operaciones.</p>
                </div>
                <div class="col-md-4 benefit-item">
                    <i class="bi bi-card-checklist"></i>
                    <h5>Menú y Pedidos Digitales</h5>
                    <p>Actualiza tu menú fácilmente, toma pedidos directamente en el sistema y envíalos a cocina al
                        instante.</p>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-6 benefit-item">
                    <i class="bi bi-receipt"></i>
                    <h5>Facturación Rápida y Precisa</h5>
                    <p>Genera facturas y recibos automáticamente con cálculos exactos, cumpliendo con las normativas
                        fiscales.</p>
                </div>
                {{-- Inventario benefit removed as per user request --}}
            </div>
        </div>
    </section>

    <section class="pricing-section container">
        <div class="d-flex justify-content-center gap-3 mb-4 pricing-controls">
            <form method="GET" action="{{ route('home') }}">
                <input type="hidden" name="intervalo" value="mes">
                <button class="btn {{ request('intervalo') === 'mes' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-calendar-month me-1"></i>Mensual
                </button>
            </form>
            <form method="GET" action="{{ route('home') }}">
                <input type="hidden" name="intervalo" value="anual">
                <button class="btn {{ request('intervalo') === 'anual' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-calendar-month me-1"></i>Anual
                </button>
            </form>
        </div>

        <div class="row row-equal-height justify-content-center g-4">
            @foreach($planes as $index => $plan)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card equal-height-card fade-in-card">
                        <div class="card-header text-white">
                            <h5 class="mb-0">{{ $plan->nombre }}</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="mb-4">{{ $plan->descripcion }}</p>

                            <div class="limites mb-4">
                                <div class="limite-item">
                                    <i class="bi bi-people"></i>
                                    <div>
                                        <small>Usuarios</small>
                                        <strong>{{ $plan->caracteristicas['limites']['usuarios'] ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="limite-item">
                                    <i class="bi bi-building"></i>
                                    <div>
                                        <small>Restaurantes</small>
                                        <strong>{{ $plan->caracteristicas['limites']['restaurantes'] ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="limite-item">
                                    <i class="bi bi-shop"></i>
                                    <div>
                                        <small>Sucursales</small>
                                        <strong>{{ $plan->caracteristicas['limites']['sucursales'] ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>

                            <small class="mt-auto d-block">Características adicionales</small>
                            <div class="caracteristicas mt-2">
                                <ul class="list-unstyled mb-0">
                                    @foreach($plan->caracteristicas['adicionales'] ?? [] as $caracteristica)
                                        <li>
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            {{ $caracteristica }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Precio</span>
                                    <span class="h5 mb-0">S/. {{ number_format($plan->precio, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span>Intervalo</span>
                                    <span class="badge rounded-pill">{{ ucfirst($plan->intervalo) }}</span>
                                </div>
                                <a href="{{ url('/contacto-planes') }}" class="button-primary btn btn-primary w-100">
                                    <i class="bi bi-check-circle me-1"></i>Elegir Plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="me-2" style="height: 40px;">
                    <span class="h4 mb-0">FoodOps</span>
                </div>
                <p class="custom-text-muted">Transformando la gestión de restaurantes con tecnología innovadora.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="hover-primary"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="hover-primary"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="hover-primary"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="hover-primary"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="mb-3">Enlaces</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="hover-primary">Inicio</a></li>
                    <li class="mb-2"><a href="{{ route('detalles.planes') }}"
                                        class="hover-primary">Planes</a></li>
                    <li class="mb-2"><a href="{{ route('contacto') }}" class="hover-primary">Contacto</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="mb-3">Soporte</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="hover-primary">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="hover-primary">Centro de Ayuda</a></li>
                    <li class="mb-2"><a href="#" class="hover-primary">Soporte Técnico</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4">
                <h5 class="mb-3">Contacto</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:info@foodops.com" class="hover-primary">info@foodops.com</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:+1234567890" class="hover-primary">+1 (234) 567-890</a>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 custom-text-muted">&copy; {{ date('Y') }} FoodOps. Todos los derechos reservados.</p>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const benefitItems = document.querySelectorAll('.benefit-item');
        const pricingCards = document.querySelectorAll('.pricing-section .card');

        const benefitObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    benefitObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        benefitItems.forEach(item => {
            benefitObserver.observe(item);
        });

        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    cardObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            delay: 200
        });

        pricingCards.forEach(card => {
            cardObserver.observe(card);
        });
    });
</script>
</body>
</html>




