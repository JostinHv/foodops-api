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
                        <h3 class="mb-1">{{ $stats['total_items'] }}</h3>
                        <small class="text-muted">En el menú</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Ítems Activos</div>
                        <h3 class="mb-1">{{ $stats['items_activos'] }}</h3>
                        <small
                            class="text-muted">{{ $stats['total_items'] > 0 ? number_format(($stats['items_activos'] / $stats['total_items']) * 100, 0) : 0 }}
                            % del total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Precio Promedio</div>
                        <h3 class="mb-1">S/ {{ number_format($stats['precio_promedio'], 2) }}</h3>
                        <small class="text-muted">Por ítem</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Más Vendido</div>
                        <h3 class="mb-1">{{ $stats['mas_vendido'] }}</h3>
                        <small class="text-muted">Ítem destacado</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de ítems del menú -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ítems del Menú</h5>
                <span class="badge bg-primary">{{ $itemsSucursales->count() }} ítems encontrados</span>
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
                            <th>Disponible</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($itemsSucursales as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->nombre }}</strong>
                                    @if($item->descripcion)
                                        <div><small class="text-muted">{{ Str::limit($item->descripcion, 50) }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->categoriaMenu->nombre ?? 'Sin categoría' }}</td>
                                <td>S/ {{ number_format($item->precio, 2) }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm {{ $item->activo ? 'btn-success' : 'btn-warning' }} toggle-activo"
                                        data-item="{{ $item->id }}">
                                        {{ $item->activo ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-sm {{ $item->disponible ? 'btn-success' : 'btn-warning' }} toggle-disponible"
                                        data-item="{{ $item->id }}">
                                        {{ $item->disponible ? 'Disponible' : 'No disponible' }}
                                    </button>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles"
                                                data-bs-toggle="modal" data-bs-target="#verItemModal"
                                                data-item="{{ $item->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarItemModal"
                                                data-item="{{ $item->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay ítems en el menú</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lista de categorías -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Categorías</h5>
                <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Categoría
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->nombre }}</td>
                                <td>{{ Str::limit($categoria->descripcion, 50) }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm {{ $categoria->activo ? 'btn-success' : 'btn-warning' }} toggle-categoria-activo"
                                        data-categoria="{{ $categoria->id }}">
                                        {{ $categoria->activo ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarCategoriaModal"
                                                data-categoria="{{ $categoria->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay categorías registradas</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nueva Categoría -->
    <div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" aria-labelledby="nuevaCategoriaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('gerente.menu.categorias.store') }}" method="POST" id="formNuevaCategoria">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaCategoriaModalLabel">Nueva Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="bi bi-tag me-1"></i>Nombre
                            </label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   placeholder="Ej: Entradas, Platos Principales, etc." required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Descripción
                            </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                      placeholder="Describe brevemente esta categoría"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="sucursal_id" class="form-label">
                                <i class="bi bi-shop me-1"></i>Sucursal
                            </label>
                            <select class="form-select" id="sucursal_id" name="sucursal_id" required>
                                <option value="">Seleccione una sucursal</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="orden_visualizacion" class="form-label">
                                <i class="bi bi-sort-numeric-down me-1"></i>Orden de Visualización
                            </label>
                            <input type="number" class="form-control" id="orden_visualizacion"
                                   name="orden_visualizacion"
                                   placeholder="Ej: 1, 2, 3..." min="1">
                            <small class="text-muted">Define el orden en que aparecerá esta categoría en el menú</small>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                            <label class="form-check-label" for="activo">
                                <i class="bi bi-toggle-on me-1"></i>Categoría activa
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
                <form action="{{ route('gerente.menu.items.store') }}" method="POST" id="formNuevoItem">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoItemModalLabel">Nuevo Ítem del Menú</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>Nombre del Ítem
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       placeholder="Ej: Ceviche de Pescado" required>
                            </div>
                            <div class="col-md-4">
                                <label for="categoria_menu_id" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Categoría
                                </label>
                                <select class="form-select" id="categoria_menu_id" name="categoria_menu_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Descripción
                            </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                      placeholder="Describe los ingredientes y características del plato"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="precio" class="form-label">
                                    <i class="bi bi-currency-dollar me-1"></i>Precio (S/)
                                </label>
                                <input type="number" class="form-control" id="precio" name="precio"
                                       placeholder="0.00" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="orden_visualizacion" class="form-label">
                                    <i class="bi bi-sort-numeric-down me-1"></i>Orden
                                </label>
                                <input type="number" class="form-control" id="orden_visualizacion"
                                       name="orden_visualizacion" placeholder="1" min="1">
                                <small class="text-muted">Orden en la categoría</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="disponible"
                                           name="disponible" checked>
                                    <label class="form-check-label" for="disponible">
                                        <i class="bi bi-check-circle me-1"></i>Disponible
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="activo"
                                           name="activo" checked>
                                    <label class="form-check-label" for="activo">
                                        <i class="bi bi-toggle-on me-1"></i>Activo
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
                            <h4 id="item-nombre"></h4>
                            <span class="badge" id="item-estado"></span>
                            <span class="badge ms-2" id="item-disponible"></span>
                        </div>
                        <div class="col-md-4 text-end">
                            <h5 id="item-precio"></h5>
                            <small class="text-muted" id="item-categoria"></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6>Descripción</h6>
                        <p id="item-descripcion"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Ítem -->
    <div class="modal fade" id="editarItemModal" tabindex="-1" aria-labelledby="editarItemModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarItem" action="" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarItemModalLabel">Editar Ítem</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nombre" class="form-label">Nombre del Ítem</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="categoria_menu_id" class="form-label">Categoría</label>
                                <select name="categoria_menu_id" class="form-select" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="precio" class="form-label">Precio (S/)</label>
                                <input type="number" name="precio" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="disponible" id="disponible">
                                    <label class="form-check-label" for="disponible">
                                        Disponible
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="activo" id="activo">
                                    <label class="form-check-label" for="activo">
                                        Activo
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

    <!-- Modal: Editar Categoría -->
    <div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarCategoria" action="" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">
                                <i class="bi bi-tag me-1"></i>Nombre
                            </label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_descripcion" class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Descripción
                            </label>
                            <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_activo" name="activo">
                            <label class="form-check-label" for="edit_activo">
                                <i class="bi bi-toggle-on me-1"></i>Categoría activa
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/gerente-sucursal/menu.js') }}"></script>
@endpush
