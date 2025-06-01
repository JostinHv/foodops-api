<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FoodOps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
            --text-muted: #7f8c8d;
            --success-color: #27ae60;
            --border-radius: 0.5rem;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        body {
            background-color: #f5f6fa;
        }

        .row-equal-height {
            display: flex;
            flex-wrap: wrap;
        }

        .row-equal-height > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }

        .equal-height-card {
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .equal-height-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .equal-height-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }

        .equal-height-card .card-header {
            border-bottom: none;
            padding: 1rem;
        }

        .card.highlight {
            border: 2px solid var(--accent-color);
        }

        .card.highlight .card-header {
            background-color: var(--accent-color);
            color: var(--text-light);
        }

        .card.highlight .text-muted {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .card.highlight .limites {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .card.highlight .limites .limite-item i,
        .card.highlight .limites .limite-item small {
            color: var(--text-light);
        }

        .card.highlight .caracteristicas li {
            color: var(--text-light);
        }

        .card.highlight .button-primary {
            background-color: var(--text-light);
            color: var(--accent-color);
        }

        .card.highlight .button-primary:hover {
            background-color: #fff;
        }

        .limites {
            background-color: var(--light-bg);
            border-radius: var(--border-radius);
            padding: 0.5rem;
            margin: 0.5rem 0;
        }

        .limites .limite-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
            background-color: rgba(255, 255, 255, 0.5);
        }

        .limites .limite-item:hover {
            background-color: rgba(255, 255, 255, 0.8);
            transform: translateX(5px);
        }

        .limites .limite-item:last-child {
            margin-bottom: 0;
        }

        .limites .limite-item i {
            margin-right: 0.75rem;
            color: var(--accent-color);
            font-size: 1rem;
        }

        .limites .limite-item small {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .limites .limite-item strong {
            color: var(--text-dark);
            font-size: 1rem;
            font-weight: 600;
        }

        /* Estilos específicos para la card destacada */
        .card.highlight .limites .limite-item {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .card.highlight .limites .limite-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .card.highlight .limites .limite-item small {
            color: var(--text-light);
        }

        .card.highlight .limites .limite-item strong {
            color: var(--text-light);
        }

        .caracteristicas {
            margin: 0.5rem 0;
        }

        .caracteristicas li {
            margin-bottom: 0.3rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            padding: 0.4rem 0.75rem;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
            background-color: rgba(255, 255, 255, 0.5);
        }

        .caracteristicas li:hover {
            background-color: rgba(255, 255, 255, 0.8);
            transform: translateX(5px);
            color: var(--text-dark);
        }

        .caracteristicas li i {
            margin-right: 0.75rem;
            color: var(--success-color);
            font-size: 1.1rem;
        }

        /* Estilos específicos para la card destacada */
        .card.highlight .caracteristicas li {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
        }

        .card.highlight .caracteristicas li:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--text-light);
        }

        .card.highlight .caracteristicas li i {
            color: var(--success-color);
        }

        .button-primary {
            background-color: var(--accent-color);
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            font-weight: 500;
        }

        .button-primary:hover {
            background-color: #2980b9;
            color: var(--text-light);
            transform: translateY(-2px);
        }

        .button-secondary {
            background-color: var(--light-bg);
            color: var(--text-dark);
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            font-weight: 500;
        }

        .button-secondary:hover {
            background-color: #e9ecef;
            color: var(--text-dark);
        }

        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: var(--border-radius);
        }

        .badge.bg-info {
            background-color: var(--accent-color) !important;
        }
    </style>
</head>
<body>
<header class="d-flex align-items-center justify-content-between p-4 mb-4 shadow-sm">
    <div class="d-flex align-items-center gap-2">
        <img src="{{asset('images/logo.png')}}" alt="Logo" style="width: 24px; height: 24px;">
        <span class="fw-bold text-danger fs-5">FoodOps</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{route('login')}}" class="btn button-secondary">Iniciar Sesión</a>
        <a href="{{route('register')}}" class="btn button-primary">Registrarse</a>
    </div>
</header>

<section class="text-center py-5">
    <h1 class="display-4 fw-bold mb-3">Sistema de Gestión de Restaurantes</h1>
    <p class="lead text-muted mb-4">
        Administra tus restaurantes, sucursales, personal, menús, órdenes y facturación en una sola plataforma
    </p>
    <a href="{{route('login')}}" class="button-primary">Comenzar ahora →</a>
</section>

<div class="d-flex justify-content-center gap-3 mb-4">
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

<section class="container pb-5">
    <div class="row row-equal-height justify-content-center g-4">
        @foreach($planes as $index => $plan)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card equal-height-card {{ $index == 1 ? 'highlight' : '' }}">
                    <div class="card-header {{ $index == 1 ? 'bg-primary' : 'bg-info' }} text-white">
                        <h5 class="mb-0">{{ $plan->nombre }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">{{ $plan->descripcion }}</p>

                        <!-- Límites del Plan -->
                        <div class="limites">
                            <div class="limite-item">
                                <i class="bi bi-people"></i>
                                <div>
                                    <small class="text-muted d-block">Usuarios</small>
                                    <strong>{{ $plan->caracteristicas['limites']['usuarios'] ?? 0 }}</strong>
                                </div>
                            </div>
                            <div class="limite-item">
                                <i class="bi bi-building"></i>
                                <div>
                                    <small class="text-muted d-block">Restaurantes</small>
                                    <strong>{{ $plan->caracteristicas['limites']['restaurantes'] ?? 0 }}</strong>
                                </div>
                            </div>
                            <div class="limite-item">
                                <i class="bi bi-shop"></i>
                                <div>
                                    <small class="text-muted d-block">Sucursales</small>
                                    <strong>{{ $plan->caracteristicas['limites']['sucursales'] ?? 0 }}</strong>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted mt-4" style="font-size: 1rem;">Características adicionales</small>
                        <!-- Características adicionales -->
                        <div class="caracteristicas">
                            <ul class="list-unstyled mb-0">
                                @foreach($plan->caracteristicas['adicionales'] ?? [] as $caracteristica)
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $caracteristica }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Precio</span>
                                <strong>S/. {{ number_format($plan->precio, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-muted">Intervalo</span>
                                <span class="badge bg-info">{{ ucfirst($plan->intervalo) }}</span>
                            </div>
                            <a href="{{ route('register') }}" class="button-primary d-block text-center">
                                <i class="bi bi-check-circle me-1"></i>Elegir Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
</body>
</html>




