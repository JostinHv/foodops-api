@extends('layouts.app')

@section('title', 'Sucursales')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-tenant/sucursales.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Sucursales</h1>
            <p class="mb-0 text-muted">Gestiona todas las sucursales de tus restaurantes</p>
        </div>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaSucursalModal">
            <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
        </a>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Total Sucursales</div>
                    <h3 class="mb-1">3</h3>
                    <small class="text-muted">En 3 restaurantes</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Capacidad Total</div>
                    <h3 class="mb-1">390</h3>
                    <small class="text-muted">Personas en todas las sucursales</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Ingresos Mensuales</div>
                    <h3 class="mb-1">$140.000</h3>
                    <small class="text-success">+18% desde el mes pasado</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Rating Promedio</div>
                    <h3 class="mb-1">4.7</h3>
                    <small class="text-muted">Basado en reseñas de clientes</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de sucursales -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Sucursales</h5>
            <span class="badge bg-primary">4 sucursales encontradas</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Restaurante</th>
                            <th>Gerente</th>
                            <th>Ubicación</th>
                            <th>Capacidad</th>
                            <th>Ingresos</th>
                            <th>Rating</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Primera sucursal -->
                        <tr>
                            <td>
                                <strong>La Cocina</strong>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>La Cocina</td>
                            <td>
                                <div>Pedro</div>
                            </td>
                            <td></td>
                            <td>120 personas</td>
                            <td>$45.000</td>
                            <td>
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </span>
                                <span class="ms-1">4.8</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Segunda sucursal -->
                        <tr>
                            <td>
                                <strong>Gourmet - Miraflores</strong>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>Gourmet</td>
                            <td>
                                <div>Sánchez</div>
                            </td>
                            <td>
                                <div>Miraflores</div>
                                <small class="text-muted">pedro.sanchez@gourmet.com</small>
                            </td>
                            <td>30 mesas</td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Tercera sucursal -->
                        <tr>
                            <td><strong>La Cocina</strong></td>
                            <td>La Cocina</td>
                            <td>
                                <div>Carmen</div>
                            </td>
                            <td></td>
                            <td>100 personas</td>
                            <td>$38.000</td>
                            <td>
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </span>
                                <span class="ms-1">4.8</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Cuarta sucursal -->
                        <tr>
                            <td><strong>Gourmet - San Isidro</strong></td>
                            <td>Gourmet</td>
                            <td>
                                <div>López</div>
                                <small class="text-muted">carmen.lopez@gourmet.com</small>
                            </td>
                            <td>San Isidro</td>
                            <td>25 mesas</td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Quinta sucursal -->
                        <tr>
                            <td><strong>Burger Palace - Centro</strong></td>
                            <td>Burger Palace</td>
                            <td>
                                <div>Roberto Díaz</div>
                                <small class="text-muted">roberto.diaz@burgerpalace.com</small>
                            </td>
                            <td></td>
                            <td>80 personas</td>
                            <td>$25.000</td>
                            <td></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
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

<!-- Modal para nueva sucursal -->
<div class="modal fade" id="nuevaSucursalModal" tabindex="-1" aria-labelledby="nuevoRestauranteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoRestauranteModalLabel">Crear Nueva Sucursal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form>
                    <!-- Restaurante -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Restaurante</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="restaurante" class="form-label">Seleccionar restaurante</label>
                                <select class="form-select" id="restaurante">
                                    <option selected disabled>Seleccione un restaurante</option>
                                    <option>La Cocina</option>
                                    <option>Gourmet</option>
                                    <option>Burger Palace</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nombre y dirección -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Nombre de la Sucursal</h6>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Ej: La Cocina Gourmet - Miraflores">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="2" placeholder="Av. Larco 123, Miraflores, Lima"></textarea>
                        </div>
                    </div>
                    
                    <!-- Capacidad -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Capacidad</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="capacidad" class="form-label">Personas</label>
                                <input type="number" class="form-control" id="capacidad" placeholder="120">
                            </div>
                            <div class="col-md-4">
                                <label for="mesas" class="form-label">Número de Mesas</label>
                                <input type="number" class="form-control" id="mesas" placeholder="30">
                            </div>
                            <div class="col-md-4">
                                <label for="personal" class="form-label">Personal</label>
                                <input type="number" class="form-control" id="personal" placeholder="25">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gerente -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Gerente de Sucursal</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="gerente" class="form-label">Seleccionar gerente</label>
                                <select class="form-select" id="gerente">
                                    <option selected disabled>Seleccione un gerente</option>
                                    <option>Pedro Sánchez</option>
                                    <option>Carmen López</option>
                                    <option>Roberto Díaz</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" placeholder="+51 999 888 777">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Horario y contacto -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Horario de Atención</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="12:00 - 23:00">
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="sucursal@restaurante.com">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dark">Crear Sucursal</button>
            </div>
        </div>
    </div>
</div>
@endsection