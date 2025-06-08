@extends('layouts.app')

@section('title', 'Dashboard Super Administrador')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/super-admin/dashboard.css') }}">
@endpush

@section('content')
    <div class="row g-3 mb-4">
        <!-- Cards de métricas principales -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Total Tenants</div>
                    <h3>123</h3>
                    <small class="text-success">+12 desde el mes pasado</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Ingresos Mensuales</div>
                    <h3>S/ 4,550.50</h3>
                    <small class="text-success">+20.1% desde el mes pasado</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Suscripciones Activas</div>
                    <h3>128</h3>
                    <small>+4 desde el mes pasado</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Usuarios Activos</div>
                    <h3>124</h3>
                    <small>+50 desde el mes pasado</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <!-- Ventas por Hora -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Resumen de Ingresos</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <!-- Espacio para gráfico de ventas por hora -->
                        <canvas id="ventasHoraChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Categorías Populares -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Distribución de Planes</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <!-- Espacio para gráfico de categorías -->
                        <canvas id="categoriasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Sección izquierda: Órdenes y Reservas -->
        <div class="col-lg-6">
            <!-- Órdenes Recientes -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Tenants Recientes</h6>
                    <small class="text-muted">Los últimos tenants registrados en el sistema</small>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Restaurante 1</strong>
                                </div>
                                <span>29/05/2025</span>
                            </div>
                            <small class="text-muted">Plan: Básico</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Restaurante 2</strong>
                                </div>
                                <span>29/05/2025</span>
                            </div>
                            <small class="text-muted">Plan: Premium</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Próximas Reservas -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Actividad Reciente</h6>
                    <small class="text-muted">Últimas acciones en el sistema</small>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Actualización de Plan</strong>
                                    <div class="text-muted">Hace 1 hora</div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Nuevo Tenant Registrado</strong>
                                    <div class="text-muted">Hace 3 horas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
