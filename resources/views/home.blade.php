<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FoodOps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        /* Asegura que todas las tarjetas tengan el mismo alto dentro de la fila */
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
        }

        .equal-height-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .equal-height-card .card-body ul {
            flex-grow: 1;
        }

        .equal-height-card .card-body a {
            margin-top: auto;
        }

        .card.highlight .text-muted {
            color: white !important;
        }

        .card.highlight .button-primary {
            background-color: white;
            color: #1f2937;;
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
        <button class="btn {{ request('intervalo') === 'mes' ? 'btn-secondary' : 'btn-outline-secondary' }}">Mensual
        </button>
    </form>
    <form method="GET" action="{{ route('home') }}">
        <input type="hidden" name="intervalo" value="año">
        <button class="btn {{ request('intervalo') === 'año' ? 'btn-secondary' : 'btn-outline-secondary' }}">Anual
        </button>
    </form>
</div>

<section class="container pb-5">
    <div class="row row-equal-height justify-content-center g-4">
        @foreach($planes as $index => $plan)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card equal-height-card {{ $index == 1 ? 'highlight' : '' }}">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-1">{{ $plan->nombre }}</h3>
                        <p class="text-muted small mb-2">{{ $plan->descripcion }}</p>
                        <p class="card-text h3 mb-3">
                            S/. {{ number_format($plan->precio, 2) }}
                            <small class="text-muted fs-6">/{{ $plan->intervalo }}</small>
                        </p>
                        <ul class="mb-4">
                            @foreach($plan->caracteristicas as $caracteristica)
                                <li>{{ $caracteristica }}</li>
                            @endforeach
                        </ul>
                        <a href="#" class="button-primary d-block text-center">Elegir</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
</body>
</html>




