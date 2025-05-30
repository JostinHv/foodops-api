@extends('layouts.app')

@section('title', 'Dashboard Gerente de Sucursal')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/gerente-sucursal/dashboard.css') }}">
@endpush

@section('content')
<div class="row g-3 mb-4">
    <!-- Cards de métricas principales -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card text-center h-100 ">
            <div class="card-body">
                <div class="fw-bold">Ventas Diarias</div>
                <h3 >3</h3>
                <small class="text-muted">+18% desde ayer</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card text-center h-100 ">
            <div class="card-body">
                <div class="fw-bold">Órdenes Activas</div>
                <h3 >12</h3>
                <small>4 en preparación, 8 servidas</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card text-center h-100 ">
            <div class="card-body">
                <div class="fw-bold">Reservas Hoy</div>
                <h3 >8</h3>
                <small>2 pendientes, 6 completadas</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card text-center h-100 ">
            <div class="card-body">
                <div class="fw-bold">Personal Activo</div>
                <h3 >14</h3>
                <small>5 meseros, 2 cajeros, 7 otros</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    <div class="col-lg-6">
        <!-- Ventas por Hora -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">Ventas por Hora</h6>
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
                <h6 class="mb-0">Categorías Populares</h6>
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
                <h6 class="mb-0">Órdenes Recientes</h6>
                <small class="text-muted">Las últimas órdenes realizadas</small>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#101</strong> - Mesa 6
                            </div>
                            <span class="badge bg-success">Lista</span>
                        </div>
                        <small class="text-muted">3 items - $25.50</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#102</strong> - Mesa 7
                            </div>
                            <span class="badge bg-primary">Servida</span>
                        </div>
                        <small class="text-muted">4 items - $51.00</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#103</strong> - Mesa 8
                            </div>
                            <span class="badge bg-warning text-dark">En preparación</span>
                        </div>
                        <small class="text-muted">5 items - $76.50</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#104</strong> - Mesa 9
                            </div>
                            <span class="badge bg-success">Lista</span>
                        </div>
                        <small class="text-muted">6 items - $102.00</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#105</strong> - Mesa 10
                            </div>
                            <span class="badge bg-primary">Servida</span>
                        </div>
                        <small class="text-muted">7 items - $127.50</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Próximas Reservas -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Próximas Reservas</h6>
                <small class="text-muted">Reservas para hoy</small>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>18:00</strong> - 3 personas
                                <div class="text-muted">Cliente: Juan Pérez 1</div>
                            </div>
                            <span class="badge bg-success">Confirmada</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>19:00</strong> - 4 personas
                                <div class="text-muted">Cliente: Juan Pérez 2</div>
                            </div>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>20:00</strong> - 5 personas
                                <div class="text-muted">Cliente: Juan Pérez 3</div>
                            </div>
                            <span class="badge bg-success">Confirmada</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>21:00</strong> - 6 personas
                                <div class="text-muted">Cliente: Juan Pérez 4</div>
                            </div>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>22:00</strong> - 7 personas
                                <div class="text-muted">Cliente: Juan Pérez 5</div>
                            </div>
                            <span class="badge bg-success">Confirmada</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection