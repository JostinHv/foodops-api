@extends('layouts.app')

@section('title', 'Grupos de Restaurantes')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Grupos de Restaurantes</h2>
            <!-- Botón que dispara el modal -->
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoGrupoModal">
                + Nuevo Grupo
            </a>
        </div>

        {{-- Tarjetas resumen --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Grupos</div>
                        <div class="fs-4">3</div>
                        <small class="text-success">+1 desde el mes pasado</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Restaurantes</div>
                        <div class="fs-4">10</div>
                        <small class="text-muted">En 3 grupos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Sucursales</div>
                        <div class="fs-4">27</div>
                        <small class="text-muted">Distribuidas en todos los grupos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Ingresos Totales</div>
                        <div class="fs-4 text-success">$259.000</div>
                        <small class="text-success">+12% desde el mes pasado</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lista de Grupos --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Lista de Grupos</h5>
                <span class="badge bg-primary">3 grupos encontrados</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead >
                            <tr>
                                <th>Grupo</th>
                                <th>Gerente</th>
                                <th>Restaurantes</th>
                                <th>Sucursales</th>
                                <th>Ingresos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Grupo Gourmet Central</strong><br>
                                    <small class="text-muted">📍 Lima, Perú</small>
                                </td>
                                <td>
                                    Carlos Mendoza<br>
                                    <small class="text-muted">carlos.mendoza@gourmet.com</small>
                                </td>
                                <td>3</td>
                                <td>8</td>
                                <td>$125.000</td>
                                <td><span class="badge bg-success">Activo</span></td>
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
                                    <strong>Fast Food Express</strong><br>
                                    <small class="text-muted">📍 Arequipa, Perú</small>
                                </td>
                                <td>
                                    Ana García<br>
                                    <small class="text-muted">ana.garcia@fastfood.com</small>
                                </td>
                                <td>5</td>
                                <td>15</td>
                                <td>$89.000</td>
                                <td><span class="badge bg-success">Activo</span></td>
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
                                    <strong>Sabores Tradicionales</strong><br>
                                    <small class="text-muted">📍 Cusco, Perú</small>
                                </td>
                                <td>
                                    Luis Rodríguez<br>
                                    <small class="text-muted">luis.rodriguez@sabores.com</small>
                                </td>
                                <td>2</td>
                                <td>4</td>
                                <td>$45.000</td>
                                <td><span class="badge bg-warning text-dark">Pendiente</span></td>
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
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal: Crear Nuevo Grupo -->
    <div class="modal fade" id="nuevoGrupoModal" tabindex="-1" aria-labelledby="nuevoGrupoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form action="{{ route('grupos.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                <h5 class="modal-title" id="nuevoGrupoModalLabel">Crear Nuevo Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                <p class="text-muted">Crea un nuevo grupo de restaurantes para organizar tu negocio</p>

                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre del Grupo</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Grupo Gourmet Central" required>
                    </div>
                    <div class="col-md-6">
                    <label for="ubicacion" class="form-label">Ubicación Principal</label>
                    <input type="text" name="ubicacion" class="form-control" placeholder="Ej: Lima, Perú" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el concepto y enfoque del grupo de restaurantes"></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="gerente" class="form-label">Gerente del Grupo</label>
                    <select name="gerente_id" class="form-select">
                        <option selected disabled>Seleccionar gerente</option>
                        
                    </select>
                    </div>
                    <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono de Contacto</label>
                    <input type="tel" name="telefono" class="form-control" placeholder="+51 999 888 777">
                    </div>
                </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark">Crear Grupo</button>
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
                <p>¿Estás seguro que deseas eliminar el grupo de restaurante?</p>
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

@push('scripts')
<script src="{{ asset('js/admin-tenant/grupo-restaurant.js') }}"></script>
@endpush