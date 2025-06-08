@extends('layouts.app')

@section('title', 'Gestión de Restaurantes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
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
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Restaurantes</div>
                        <h3 class="mb-1">{{ $restaurantes->count() }}</h3>
                        <small class="text-muted">En {{ $grupos->count() }} grupos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Restaurantes Activos</div>
                        <h3 class="mb-1">{{ $restaurantes->where('activo', true)->count() }}</h3>
                        <small class="text-success">Operativos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Restaurantes Inactivos</div>
                        <h3 class="mb-1">{{ $restaurantes->where('activo', false)->count() }}</h3>
                        <small class="text-warning">En mantenimiento</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Grupos</div>
                        <h3 class="mb-1">{{ $grupos->count() }}</h3>
                        <small class="text-muted">Grupos activos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de restaurantes -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Restaurantes</h5>
                <span class="badge bg-primary">{{ $restaurantes->count() }} restaurantes encontrados</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Restaurante</th>
                            <th>Grupo</th>
                            <th>Tipo</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($restaurantes as $restaurante)
                            <tr>
                                <td>
                                    @if($restaurante->logo)
                                        <img src="{{ Storage::url($restaurante->logo->url) }}" alt="Logo"
                                             class="img-thumbnail" style="max-width: 50px;">
                                    @else
                                        <div class="bg-light rounded p-2 text-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-building"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $restaurante->nombre_legal }}</strong>
                                    @if($restaurante->nro_ruc)
                                        <div><small class="text-muted">{{ $restaurante->nro_ruc }}</small></div>
                                    @endif
                                </td>
                                <td>{{ $restaurante->grupoRestaurantes->nombre ?? 'Sin grupo' }}</td>
                                <td>{{ $restaurante->tipo_negocio }}</td>
                                <td>
                                    <div>{{ $restaurante->telefono }}</div>
                                    <small class="text-muted">{{ $restaurante->email }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('tenant.restaurantes.toggle-activo', $restaurante) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="btn btn-sm {{ $restaurante->activo ? 'btn-success' : 'btn-warning' }}">
                                            {{ $restaurante->activo ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles"
                                                data-bs-toggle="modal" data-bs-target="#verRestauranteModal"
                                                data-restaurante="{{ $restaurante->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editarRestauranteModal"
                                                data-restaurante="{{ $restaurante->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Crear Nuevo Restaurante -->
    <div class="modal fade" id="nuevoRestauranteModal" tabindex="-1" aria-labelledby="nuevoRestauranteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tenant.restaurantes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoRestauranteModalLabel">Crear Nuevo Restaurante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Agrega un nuevo restaurante a tu organización</p>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_legal" class="form-label">Nombre Legal</label>
                                <input type="text" name="nombre_legal" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nro_ruc" class="form-label">Número RUC</label>
                                <input type="text" name="nro_ruc" class="form-control" maxlength="11">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="grupo_restaurant_id" class="form-label">Grupo</label>
                                <select name="grupo_restaurant_id" class="form-select">
                                    <option value="">Seleccionar grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_negocio" class="form-label">Tipo de Negocio</label>
                                <input type="text" name="tipo_negocio" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" name="latitud" class="form-control" step="any">
                            </div>
                            <div class="col-md-6">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" name="longitud" class="form-control" step="any">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sitio_web_url" class="form-label">Sitio Web</label>
                            <input type="url" name="sitio_web_url" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
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

    <!-- Modal: Editar Restaurante -->
    <div class="modal fade" id="editarRestauranteModal" tabindex="-1" aria-labelledby="editarRestauranteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarRestaurante" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarRestauranteModalLabel">Editar Restaurante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_legal" class="form-label">Nombre Legal</label>
                                <input type="text" name="nombre_legal" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nro_ruc" class="form-label">Número RUC</label>
                                <input type="text" name="nro_ruc" class="form-control" maxlength="11">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="grupo_restaurant_id" class="form-label">Grupo</label>
                                <select name="grupo_restaurant_id" class="form-select">
                                    <option value="">Seleccionar grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_negocio" class="form-label">Tipo de Negocio</label>
                                <input type="text" name="tipo_negocio" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" name="latitud" class="form-control" step="any">
                            </div>
                            <div class="col-md-6">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" name="longitud" class="form-control" step="any">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sitio_web_url" class="form-label">Sitio Web</label>
                            <input type="url" name="sitio_web_url" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div id="current-logo" class="mt-2"></div>
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

    <!-- Modal: Ver Detalles Restaurante -->
    <div class="modal fade" id="verRestauranteModal" tabindex="-1" aria-labelledby="verRestauranteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verRestauranteModalLabel">Detalles del Restaurante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 id="restaurante-nombre"></h4>
                            <span id="restaurante-estado" class="badge"></span>
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="mb-1"><strong>Grupo:</strong> <span id="restaurante-grupo"></span></p>
                            <p class="mb-1"><strong>Tipo:</strong> <span id="restaurante-tipo"></span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Información Legal</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>RUC:</span>
                                    <span id="restaurante-ruc"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Dirección:</span>
                                    <span id="restaurante-direccion"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ubicación:</span>
                                    <span id="restaurante-ubicacion"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Contacto</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Teléfono:</span>
                                    <span id="restaurante-telefono"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Email:</span>
                                    <span id="restaurante-email"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Sitio Web:</span>
                                    <span id="restaurante-web"></span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div id="restaurante-logo" class="text-center mb-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-tenant/restaurants.js') }}"></script>
@endpush
