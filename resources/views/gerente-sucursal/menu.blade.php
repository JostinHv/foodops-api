@extends('layouts.app')

@section('title', 'Menu')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/gerente-sucursal/menu.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestión de Menú</h1>
            <p class="mb-0 text-muted">Administra categorías, ítems y precios del menú</p>
        </div>
        <div>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
            <i class="bi bi-plus-circle me-2"></i>Nueva Categoria
        </a>
        <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#nuevoItemModal">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Ítem
        </a>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Total Ítems</div>
                    <h3 class="mb-1">127</h3>
                    <small class="text-success">+12 este mes</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Ítems Activos</div>
                    <h3 class="mb-1">98</h3>
                    <small class="text-muted">77% del total</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Precio Promedio</div>
                    <h3 class="mb-1">S/ 28.50</h3>
                    <small class="text-success">+5% vs mes anterior</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Más Vendido</div>
                    <h3 class="mb-1">Lomo Saltado</h3>
                    <small class="text-muted">45 órdenes hoy</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de ítems del menú -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ítems del Menú</h5>
            <span class="badge bg-primary">127 ítems encontrados</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ítem</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Ventas Hoy</th>
                            <th>Tiempo Prep.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Ceviche de Pescado</strong>
                            </td>
                            <td>Entradas</td>
                            <td>S/ 32.00</td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>12</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>15min
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles" data-bs-toggle="modal" data-bs-target="#verItemModal">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar" data-bs-toggle="modal" data-bs-target="#editarItemModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar" data-bs-toggle="modal" data-bs-target="#eliminarItemModal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Lomo Saltado</strong>
                            </td>
                            <td>Principales</td>
                            <td>S/ 28.50</td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>45</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>20min
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
                                <strong>Ají de Gallina</strong>
                            </td>
                            <td>Principales</td>
                            <td>S/ 25.00</td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>23</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>25min
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
                                <strong>Anticuchos</strong>
                            </td>
                            <td>Entradas</td>
                            <td>S/ 18.00</td>
                            <td>
                                <span class="badge bg-secondary">Inactivo</span>
                            </td>
                            <td>0</td>
                            <td>
                                <i class="bi bi-clock me-1"></i>12min
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

<!-- Modal: Nueva Categoría -->
<div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" aria-labelledby="nuevaCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevaCategoriaModalLabel">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_categoria" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_categoria" placeholder="Ej: Entradas" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_categoria" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_categoria" rows="3" placeholder="Descripción de la categoría"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="categoria_activa" checked>
                        <label class="form-check-label" for="categoria_activa">
                            Categoría activa
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Crear Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Ítem del Menú -->
<div class="modal fade" id="nuevoItemModal" tabindex="-1" aria-labelledby="nuevoItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoItemModalLabel">Nuevo Ítem del Menú</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="nombre_item" class="form-label">Nombre del Ítem</label>
                            <input type="text" class="form-control" id="nombre_item" placeholder="Ej: Ceviche de Pescado" required>
                        </div>
                        <div class="col-md-4">
                            <label for="categoria_item" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria_item" required>
                                <option selected disabled>Seleccionar categoría</option>
                                <option value="Entradas">Entradas</option>
                                <option value="Principales">Platos Principales</option>
                                <option value="Postres">Postres</option>
                                <option value="Bebidas">Bebidas</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion_item" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_item" rows="3" placeholder="Descripción detallada del plato"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="precio_item" class="form-label">Precio (S/)</label>
                            <input type="number" class="form-control" id="precio_item" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tiempo_preparacion" class="form-label">Tiempo Prep. (min)</label>
                            <input type="number" class="form-control" id="tiempo_preparacion" placeholder="15" min="1" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="disponible" checked>
                                <label class="form-check-label" for="disponible">
                                    Disponible
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="destacado">
                                <label class="form-check-label" for="destacado">
                                    Ítem destacado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Crear Ítem</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Ver Detalles del Ítem -->
<div class="modal fade" id="verItemModal" tabindex="-1" aria-labelledby="verItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verItemModalLabel">Detalles del Ítem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h4 id="itemNombre">Ceviche de Pescado</h4>
                        <span class="badge bg-success" id="itemEstado">Activo</span>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 id="itemPrecio">S/ 32.00</h5>
                        <small class="text-muted" id="itemCategoria">Entradas</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Descripción</h6>
                    <p id="itemDescripcion">Delicioso ceviche de pescado fresco marinado en limón con cebolla, ají y cilantro.</p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <h6>Tiempo de Preparación</h6>
                        <p id="itemTiempo"><i class="bi bi-clock"></i> 15 minutos</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Ventas Hoy</h6>
                        <p id="itemVentas">12 órdenes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Editar Ítem -->
<div class="modal fade" id="editarItemModal" tabindex="-1" aria-labelledby="editarItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditarItem" action="#" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editItemId" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarItemModalLabel">Editar Ítem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenido similar al modal de nuevo ítem pero para edición -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="editNombre" class="form-label">Nombre del Ítem</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <div class="col-md-4">
                            <label for="editCategoria" class="form-label">Categoría</label>
                            <select class="form-select" id="editCategoria" name="categoria" required>
                                <option value="Entradas">Entradas</option>
                                <option value="Principales">Platos Principales</option>
                                <option value="Postres">Postres</option>
                                <option value="Bebidas">Bebidas</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editDescripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editPrecio" class="form-label">Precio (S/)</label>
                            <input type="number" class="form-control" id="editPrecio" name="precio" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label for="editTiempo" class="form-label">Tiempo Prep. (min)</label>
                            <input type="number" class="form-control" id="editTiempo" name="tiempo_preparacion" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="editEstado" class="form-label">Estado</label>
                            <select class="form-select" id="editEstado" name="estado">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editDisponible" name="disponible">
                                <label class="form-check-label" for="editDisponible">
                                    Disponible
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editDestacado" name="destacado">
                                <label class="form-check-label" for="editDestacado">
                                    Ítem destacado
                                </label>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Confirmar Eliminación -->
<div class="modal fade" id="eliminarItemModal" tabindex="-1" aria-labelledby="eliminarItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarItemModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas eliminar el ítem <strong id="itemEliminarNombre">Ceviche de Pescado</strong>?</p>
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
@endsection