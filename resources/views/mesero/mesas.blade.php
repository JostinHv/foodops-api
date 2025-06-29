@extends('layouts.app')

@section('title', 'Mesas')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mesero/mesas.css') }}">
@endpush

@section('content')
<div data-dynamic-content>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Estado de Mesas</h2>
        <p class="mb-0 text-muted">Visualiza y administra el estado de las mesas</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchMesa" placeholder="Buscar mesa...">
                <button class="btn btn-outline-secondary" type="button">Buscar</button>
            </div>
        </div>
    </div>

    <div class="row" id="mesas-container">
        <!-- Mesa 1 - Ocupada -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card mesa-card border-start border-4 border-danger">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mesa #1</h5>
                    <span class="badge bg-danger">Ocupada</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span><i class="bi bi-people-fill me-2"></i>4 personas</span>
                    </div>
                    
                    <div class="orden-info mb-3 p-3 bg-light rounded">
                        <h6 class="fw-bold">Orden #101</h6>
                        <p class="mb-1">5 items - $87.50</p>
                        <span class="badge bg-success">Servida</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-primary btn-sm ver-orden-btn" data-orden="101">
                            <i class="bi bi-eye me-1"></i>Ver Orden
                        </button>
                        <button class="btn btn-outline-secondary btn-sm cambiar-estado-btn" data-mesa="1">
                            <i class="bi bi-arrow-repeat me-1"></i>Cambiar Estado
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mesa 2 - Libre -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card mesa-card border-start border-4 border-success">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mesa #2</h5>
                    <span class="badge bg-success">Libre</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span><i class="bi bi-people-fill me-2"></i>2 personas</span>
                    </div>
                    
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-door-open display-6"></i>
                        <p class="mb-0">Mesa disponible</p>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-outline-secondary btn-sm cambiar-estado-btn" data-mesa="2">
                            <i class="bi bi-arrow-repeat me-1"></i>Cambiar Estado
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mesa 3 - Reservada -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card mesa-card border-start border-4 border-warning">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mesa #3</h5>
                    <span class="badge bg-warning text-dark">Reservada</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span><i class="bi bi-people-fill me-2"></i>4 personas</span>
                    </div>
                    
                    <div class="reserva-info mb-3 p-3 bg-light rounded">
                        <h6 class="fw-bold">Ana Gómez</h6>
                        <p class="mb-0">19:30 - 4 personas</p>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-outline-secondary btn-sm cambiar-estado-btn" data-mesa="3">
                            <i class="bi bi-arrow-repeat me-1"></i>Cambiar Estado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Más mesas pueden ser agregadas dinámicamente con JavaScript -->
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="estadoModal" tabindex="-1" aria-labelledby="estadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="estadoModalLabel">Cambiar estado de la mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cambiarEstadoForm">
                    <input type="hidden" id="mesaId" name="mesa_id">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo estado</label>
                        <select class="form-select" id="nuevoEstado" name="nuevo_estado">
                            <option value="libre">Libre</option>
                            <option value="ocupada">Ocupada</option>
                            <option value="reservada">Reservada</option>
                            <option value="mantenimiento">En mantenimiento</option>
                        </select>
                    </div>
                    <div class="mb-3" id="reservaFields" style="display: none;">
                        <label for="nombreReserva" class="form-label">Nombre de reserva</label>
                        <input type="text" class="form-control" id="nombreReserva" name="nombre_reserva">
                        <label for="horaReserva" class="form-label mt-2">Hora de reserva</label>
                        <input type="time" class="form-control" id="horaReserva" name="hora_reserva">
                        <label for="personasReserva" class="form-label mt-2">Número de personas</label>
                        <input type="number" class="form-control" id="personasReserva" name="personas_reserva" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarCambio">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/mesero/mesas.js') }}"></script>
@endpush