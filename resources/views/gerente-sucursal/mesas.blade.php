@extends('layouts.app')

@section('title', 'Mesas')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/gerente-sucursal/mesas.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestión de Mesas</h1>
            <p class="mb-0 text-muted">Administra el estado y configuración de mesas</p>
        </div>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaMesaModal">
            <i class="bi bi-plus-circle me-2"></i>Nueva Mesa
        </a>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Total Mesas</div>
                    <h3 class="mb-1">10</h3>
                    <small class="text-muted">40 asientos totales</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Ocupación</div>
                    <h3 class="mb-1">60%</h3>
                    <small class="text-muted">6 de 10 mesas</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Tiempo Promedio</div>
                    <h3 class="mb-1">1h 15min</h3>
                    <small class="text-muted">Por mesa ocupada</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Ingresos/Mesa</div>
                    <h3 class="mb-1">S/ 85.50</h3>
                    <small class="text-muted">Promedio hoy</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de mesas -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mesas del Restaurante</h5>
            <span class="badge bg-primary">5 mesas encontradas</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mesa</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th>Mesero</th>
                            <th>Tiempo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Mesa 1</strong></td>
                            <td>4 personas</td>
                            <td>
                                <span class="badge bg-success">Libre</span>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles" data-bs-toggle="modal" data-bs-target="#verMesaModal">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar" data-bs-toggle="modal" data-bs-target="#editarMesaModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mesa 2</strong></td>
                            <td>2 personas</td>
                            <td>
                                <span class="badge bg-warning text-dark">Ocupada</span>
                            </td>
                            <td>Carlos M.</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>45min
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mesa 3</strong></td>
                            <td>6 personas</td>
                            <td>
                                <span class="badge bg-info">Reservada</span>
                            </td>
                            <td>-</td>
                            <td>19:30</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mesa 4</strong></td>
                            <td>4 personas</td>
                            <td>
                                <span class="badge bg-secondary">En limpieza</span>
                            </td>
                            <td>-</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>5min
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mesa 5</strong></td>
                            <td>2 personas</td>
                            <td>
                                <span class="badge bg-warning text-dark">Ocupada</span>
                            </td>
                            <td>Ana L.</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>20min
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nueva Mesa -->
<div class="modal fade" id="nuevaMesaModal" tabindex="-1" aria-labelledby="nuevaMesaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formNuevaMesa" action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevaMesaModalLabel">Nueva Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numero_mesa" class="form-label">Número de Mesa</label>
                            <input type="text" class="form-control" id="numero_mesa" name="numero_mesa" placeholder="Ej: 11" required>
                        </div>
                        <div class="col-md-6">
                            <label for="capacidad" class="form-label">Capacidad</label>
                            <select class="form-select" id="capacidad" name="capacidad" required>
                                <option selected disabled>Seleccionar</option>
                                <option value="2 personas">2 personas</option>
                                <option value="4 personas">4 personas</option>
                                <option value="6 personas">6 personas</option>
                                <option value="8 personas">8 personas</option>
                                <option value="10 personas">10 personas</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Características especiales de la mesa"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Crear Mesa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/gerente-sucursal/mesas.js') }}"></script>
@endpush