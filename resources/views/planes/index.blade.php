@extends('layouts.app')

@section('title', 'Planes - FoodOps')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 mb-3">Nuestros Planes</h1>
        <p class="lead text-muted">Elige el plan que mejor se adapte a las necesidades de tu restaurante</p>
    </div>

    <div class="d-flex justify-content-center gap-3 mb-4">
        <form method="GET" action="{{ route('planes') }}">
            <input type="hidden" name="intervalo" value="mes">
            <button class="btn {{ request('intervalo') === 'mes' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-calendar-month me-1"></i>Mensual
            </button>
        </form>
        <form method="GET" action="{{ route('planes') }}">
            <input type="hidden" name="intervalo" value="anual">
            <button class="btn {{ request('intervalo') === 'anual' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-calendar-month me-1"></i>Anual
            </button>
        </form>
    </div>

    <div class="row row-equal-height justify-content-center g-4">
        @foreach($planes as $index => $plan)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card equal-height-card {{ $index == 1 ? 'highlight' : '' }}">
                    <div class="card-header {{ $index == 1 ? 'bg-primary' : 'bg-info' }} text-white">
                        <h5 class="mb-0">{{ $plan->nombre }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">{{ $plan->descripcion }}</p>

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

                        <small class="text-muted mt-4 d-block">Características adicionales</small>
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
                            <a href="{{ route('contacto.planes') }}" class="button-primary">
                                <i class="bi bi-check-circle me-1"></i>Elegir Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 