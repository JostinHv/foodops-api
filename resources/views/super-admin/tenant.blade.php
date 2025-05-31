@extends('layouts.app')

@section('title', 'Tenant')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/tenant.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Gestión de Tenants</h1>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoTenantModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Tenant
            </a>
        </div>

        <!-- Gestión de Tenants -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Tenants</h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-3">3 tenants encontrados</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Plan</th>
                                <th>Restaurantes</th>
                                <th>Usuarios</th>
                                <th>Estado</th>
                                <th>Último Acceso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Grupo Gastronómico Lima</strong><br>
                                    <small class="text-muted">admin@gruppogastronomicolima.com</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Premium</span>
                                </td>
                                <td>8</td>
                                <td>45</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>13/5/2025</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles"
                                            data-bs-toggle="modal" data-bs-target="#detallesTenantModal">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#editarTenantModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Desactivar"  data-bs-toggle="modal" data-bs-target="#desactivarItemModal">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Restaurantes del Norte</strong><br>
                                    <small class="text-muted">contacto@restaurantesdelnorte.com</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">Estándar</span>
                                </td>
                                <td>3</td>
                                <td>18</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>12/5/2025</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#eliminarItemModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Desactivar"  data-bs-toggle="modal" data-bs-target="#desactivarItemModal">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Sabores Tradicionales</strong><br>
                                    <small class="text-muted">info@saborestadionales.com</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">Básico</span>
                                </td>
                                <td>1</td>
                                <td>5</td>
                                <td>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </td>
                                <td>10/5/2025</td>
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
                                        <button class="btn btn-sm btn-outline-danger" title="Desactivar"  data-bs-toggle="modal" data-bs-target="#desactivarItemModal">
                                            <i class="bi bi-person-dash"></i>
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

    <!-- Modal: Nuevo Tenant -->
    <div class="modal fade" id="nuevoTenantModal" tabindex="-1" aria-labelledby="nuevoTenantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formNuevoTenant" action="#" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoTenantModalLabel">Crear Nuevo Tenant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">Nombre de la Empresa</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_empresa" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa"
                                    placeholder="Ej: Grupo Gastronómico Lima" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="admin@empresa.com" required>
                            </div>
                        </div>

                        <h6 class="mb-3">Plan de Suscripción</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="plan" class="form-label">Plan</label>
                                <select class="form-select" id="plan" name="plan" required>
                                    <option selected disabled>Selecciona un plan</option>
                                    <option value="Básico">Básico</option>
                                    <option value="Estándar">Estándar</option>
                                    <option value="Premium">Premium</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                    placeholder="+51 999 888 777" required>
                            </div>
                        </div>

                        <h6 class="mb-3">Dirección</h6>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección completa</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"
                                placeholder="Ingrese la dirección completa de la empresa" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Tenant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Detalles del Tenant -->
    <div class="modal fade" id="detallesTenantModal" tabindex="-1" aria-labelledby="detallesTenantModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesTenantModalLabel">Detalles del Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Información General -->
                            <div class="mb-4">
                                <h6 class="section-title mb-3">Información General</h6>
                                <div class="row mb-2">
                                    <div class="col-sm-3 fw-bold">Nombre</div>
                                    <div class="col-sm-9" id="tenant-nombre">Grupo Gastronómico Lima</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-3 fw-bold">Email</div>
                                    <div class="col-sm-9" id="tenant-email">admin@grupogastronomicolima.com</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-3 fw-bold">Teléfono</div>
                                    <div class="col-sm-9" id="tenant-telefono">+51 999 888 777</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 fw-bold">Dirección</div>
                                    <div class="col-sm-9" id="tenant-direccion">Av. Larco 123, Miraflores</div>
                                </div>
                            </div>

                            <!-- Suscripción -->
                            <div class="mb-4">
                                <h6 class="section-title mb-3">Suscripción</h6>
                                <div class="row mb-2">
                                    <div class="col-sm-3 fw-bold">Plan Actual</div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-success" id="tenant-plan">Premium</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-3 fw-bold">Precio Mensual</div>
                                    <div class="col-sm-9" id="tenant-precio">$199.99</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-3 fw-bold">Próxima Facturación</div>
                                    <div class="col-sm-9" id="tenant-facturacion">15/6/2025</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 fw-bold">Estado</div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-success" id="tenant-estado">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Estadísticas -->
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <div class="mb-4">
                                        <h2 class="mb-0 fw-bold" id="tenant-restaurantes">8</h2>
                                        <small class="text-muted">Restaurantes</small>
                                    </div>
                                    <div class="mb-4">
                                        <h2 class="mb-0 fw-bold" id="tenant-usuarios">45</h2>
                                        <small class="text-muted">Usuarios</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Fecha de Registro</h6>
                                        <p class="mb-0" id="tenant-registro">15/1/2024</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Confirmar Eliminación -->
    <div class="modal fade" id="eliminarItemModal" tabindex="-1" aria-labelledby="eliminarItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarItemModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar el Tenant?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminarItem" action="#" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar Ítem</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Confirmar Desactivar -->
    <div class="modal fade" id="desactivarItemModal" tabindex="-1" aria-labelledby="eliminarItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarItemModalLabel">Confirmar Desactivación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas desactivar el Tenant?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminarItem" action="#" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Desactivar Tenant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
