@extends('layouts.app')

@section('title', 'Usuarios')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-tenant/usuarios.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Usuarios</h1>
            <p class="mb-0 text-muted">Gestiona todas los usuarios de tu organización</p>
        </div>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
        </a>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Total Usuarios</div>
                    <h3 class="mb-1">5</h3>
                    <small class="text-success">+3 desde el mes pasado</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Usuarios activos</div>
                    <h3 class="mb-1">4</h3>
                    <small class="text-muted">80% del Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Gerentes</div>
                    <h3 class="mb-1">3</h3>
                    <small class="text-muted">Gerentes de grupo y sucursal</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100 ">
                <div class="card-body">
                    <div class="fw-bold">Personal Operativo</div>
                    <h3 class="mb-1">2</h3>
                    <small class="text-muted">Cajeros, meseros y recepcionistas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de usuarios -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Usuarios</h5>
            <span class="badge bg-primary">5 usuarios encontrados</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Restaurante/Sucursal</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th>Último Acceso</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Pedro Sánchez</strong>
                                <div class="text-muted">pedro.sanchez@gourmet.com</div>
                            </td>
                            <td>Gerente de Sucursal</td>
                            <td>La Cocina - Miraflores</td>
                            <td>+51 999 888</td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>15/5/2024</td>
                            <td>
                                <i class="bi bi-check-circle-fill text-success"></i>
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
                                <strong>Carmen López</strong>
                                <div class="text-muted">carmen.lopez@gourmet.com</div>
                            </td>
                            <td>Gerente de Sucursal</td>
                            <td>La Cocina - San Isidro</td>
                            <td>+51 888 777</td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>14/5/2024</td>
                            <td>
                                <i class="bi bi-check-circle-fill text-success"></i>
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
                                <strong>Roberto Díaz</strong>
                                <div class="text-muted">roberto.diaz@burg.</div>
                            </td>
                            <td>Gerente de Sucursal</td>
                            <td>Burger Palace</td>
                            <td>+51 777 666</td>
                            <td>
                                <span class="badge bg-secondary">Inactivo</span>
                            </td>
                            <td>10/5/2024</td>
                            <td>
                                <i class="bi bi-x-circle-fill text-danger"></i>
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

<!-- Modal: Crear Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-labelledby="nuevoUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoUsuarioModalLabel">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Agrega un nuevo usuario a tu organización</p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre_completo" class="form-control" placeholder="Ej: Pedro Sánchez" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control" placeholder="+51 999 888 777" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="restaurante_id" class="form-label">Restaurante</label>
                            <select name="restaurante_id" class="form-select" required>
                                <option selected disabled>Seleccionar restaurante</option>
                                <!-- Opciones dinámicas irían aquí -->
                                <option value="1">La Cocina Gourmet</option>
                                <option value="2">Burger Palace</option>
                                <option value="3">Tradición Criolla</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="sucursal_id" class="form-label">Sucursal</label>
                            <select name="sucursal_id" class="form-select" required>
                                <option selected disabled>Seleccionar sucursal</option>
                                <!-- Opciones dinámicas irían aquí -->
                                <option value="1">Miraflores</option>
                                <option value="2">San Isidro</option>
                                <option value="3">Surco</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="pedro.sanchez@empresa.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="rol_id" class="form-label">Rol</label>
                            <select name="rol_id" class="form-select" required>
                                <option selected disabled>Seleccionar rol</option>
                                <!-- Opciones dinámicas irían aquí -->
                                <option value="1">Gerente de Sucursal</option>
                                <option value="2">Administrador</option>
                                <option value="3">Cajero</option>
                                <option value="4">Mesero</option>
                                <option value="5">Cocinero</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permisos</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="manage_staff" id="permiso1">
                                    <label class="form-check-label" for="permiso1">Manage Staff</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="manage_orders" id="permiso2">
                                    <label class="form-check-label" for="permiso2">Manage Orders</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="view_menu" id="permiso3">
                                    <label class="form-check-label" for="permiso3">View Menu</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="view_reports" id="permiso4">
                                    <label class="form-check-label" for="permiso4">View Reports</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="manage_payments" id="permiso5">
                                    <label class="form-check-label" for="permiso5">Manage Payments</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="manage_inventory" id="permiso6">
                                    <label class="form-check-label" for="permiso6">Manage Inventory</label>
                                </div>
                                <!-- Puedes agregar más permisos aquí -->
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-tenant/usuarios.js') }}"></script>
@endpush