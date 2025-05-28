@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-tenant/dashboard.css') }}">
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small>Total Restaurantes</small>
            <h3>12</h3>
            <small class="text-success">+2 desde el mes pasado</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small>Ingresos Mensuales</small>
            <h3>$24,565.00</h3>
            <small class="text-success">+15.2% desde el mes pasado</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small>Plan Actual</small>
            <h3>Premium</h3>
            <small>Renovación: 15/06/2025</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small>Total Usuarios</small>
            <h3>48</h3>
            <small class="text-success">+5 desde el mes pasado</small>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" href="#">Resumen</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Restaurantes</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Usuarios</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Suscripción</a></li>
</ul>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm p-3">
            <h6>Resumen de Ventas</h6>
            <div class="bg-light">
                Gráfico de ventas
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm p-3">
            <h6>Distribución por Restaurante</h6>
            <div class="bg-light">
                Gráfico de distribución
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card shadow-sm p-3">
            <h6>Restaurantes Destacados</h6>
            <small class="text-muted">Los restaurantes con mejor desempeño</small>
            <ul class="list-group list-group-flush mt-2">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Restaurante 1 <span>$10,000.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Restaurante 2 <span>$5,000.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Restaurante 3 <span>$3,333.33</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Restaurante 4 <span>$2,500.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Restaurante 5 <span>$2,000.00</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm p-3">
            <h6>Actividad Reciente</h6>
            <small class="text-muted">Últimas acciones en el sistema</small>
            <ul class="list-group list-group-flush mt-2">
                <li class="list-group-item">
                    <strong>Usuario creado</strong><br><small>Hace 1 hora</small>
                </li>
                <li class="list-group-item">
                    <strong>Menú actualizado</strong><br><small>Hace 2 horas</small>
                </li>
                <li class="list-group-item">
                    <strong>Nueva sucursal añadida</strong><br><small>Hace 3 horas</small>
                </li>
                <li class="list-group-item">
                    <strong>Usuario creado</strong><br><small>Hace 4 horas</small>
                </li>
                <li class="list-group-item">
                    <strong>Menú actualizado</strong><br><small>Hace 5 horas</small>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-tenant/dashboard.js') }}"></script>
@endpush
