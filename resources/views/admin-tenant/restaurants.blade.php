@extends('layouts.app')

@section('title', 'Gestión de Restaurantes')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-tenant/restaurants.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Restaurantes</h1>
            <p class="mb-0 text-muted">Gestiona todos los restaurantes de tu organización</p>
        </div>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoRestauranteModal">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Restaurante
        </a>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Total Restaurantes</div>
                    <h3 class="mb-1">3</h3>
                    <small class="text-muted">En 3 grupos</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Total Sucursales</div>
                    <h3 class="mb-1">10</h3>
                    <small class="text-muted">Distribuidas en todos los restaurantes</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Ingresos Totales</div>
                    <h3 class="mb-1">$195.000</h3>
                    <small class="text-success">+15% desde el mes pasado</small>
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

    <!-- Lista de restaurantes -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Restaurantes</h5>
            <span class="badge bg-primary">3 restaurantes encontrados</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Restaurante</th>
                            <th>Grupo</th>
                            <th>Cocina</th>
                            <th>Gerente</th>
                            <th>Sucursales</th>
                            <th>Ingresos</th>
                            <th>Rating</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>La Cocina Gourmet</strong>
                                <span class="badge bg-success ms-2">Activo</span>
                            </td>
                            <td>Grupo Gourmet Central</td>
                            <td>Internacional</td>
                            <td>
                                <div>Maria González</div>
                                <small class="text-muted">maria.gonzalez@gourmet.com</small>
                            </td>
                            <td>3</td>
                            <td>$85.000</td>
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
                        <tr>
                            <td>
                                <strong>Burger Palace</strong>
                                <span class="badge bg-success ms-2">Activo</span>
                            </td>
                            <td>Fast Food Express</td>
                            <td>Americana</td>
                            <td>
                                <div>Carlos Ruiz</div>
                                <small class="text-muted">carlos.ruiz@burgerpalace.com</small>
                            </td>
                            <td>5</td>
                            <td>$65.000</td>
                            <td>
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </span>
                                <span class="ms-1">4.0</span>
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
                        <tr>
                            <td>
                                <strong>Tradición Criolla</strong>
                                <span class="badge bg-warning text-dark ms-2">Mantenimiento</span>
                            </td>
                            <td>Sabores Tradicionales</td>
                            <td>Peruana</td>
                            <td>
                                <div>Ana Vargas</div>
                                <small class="text-muted">ana.vargas@tradicion.com</small>
                            </td>
                            <td>2</td>
                            <td>$45.000</td>
                            <td>
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </span>
                                <span class="ms-1">4.7</span>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Crear Nuevo Restaurante -->
<div class="modal fade" id="nuevoRestauranteModal" tabindex="-1" aria-labelledby="nuevoRestauranteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoRestauranteModalLabel">Crear Nuevo Restaurante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Agrega un nuevo restaurante a tu organización</p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Restaurante</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: La Cocina Gourmet" required>
                        </div>
                        <div class="col-md-6">
                            <label for="grupo_id" class="form-label">Grupo</label>
                            <select name="grupo_id" class="form-select" required>
                                <option selected disabled>Seleccionar grupo</option>
                                
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el concepto y especialidad del restaurante"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipo_cocina" class="form-label">Tipo de Cocina</label>
                            <select name="tipo_cocina" class="form-select" required>
                                <option selected disabled>Seleccionar tipo</option>
                                <option value="Internacional">Internacional</option>
                                <option value="Peruana">Peruana</option>
                                <option value="Americana">Americana</option>
                                <option value="Italiana">Italiana</option>
                                <option value="Asiática">Asiática</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="capacidad" class="form-label">Capacidad</label>
                            <input type="number" name="capacidad" class="form-control" placeholder="120" min="1" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gerente_id" class="form-label">Gerente</label>
                            <select name="gerente_id" class="form-select" required>
                                <option selected disabled>Seleccionar gerente</option>
                                
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="horario" class="form-label">Horario de Atención</label>
                            <input type="text" name="horario" class="form-control" placeholder="12:00 - 23:00" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control" placeholder="+51 999 888 777" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="info@restaurante.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Av. Larco 123, Miraflores" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Crear Restaurante</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-tenant/restaurants.js') }}"></script>
@endpush