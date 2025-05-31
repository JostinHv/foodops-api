@extends('layouts.app')

@section('title', 'Planes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/planes.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Planes de Suscripción</h1>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoPlanModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Plan
            </a>
        </div>

        <div class="row g-4">
            <!-- Plan Básico -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Básico</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Perfecto para restaurantes pequeños que están comenzando</p>

                        <div class="d-flex align-items-end mb-4">
                            <h2 class="mb-0 fw-bold">$49.99</h2>
                            <span class="text-muted ms-1">/mes</span>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Restaurantes: 1
                            </li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Usuarios: 5</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Almacenamiento: 5
                                GB</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Soporte: Email</li>
                            <li class="mb-2"><i class="bi bi-x-circle-fill text-danger me-2"></i> Analíticas</li>
                            <li class="mb-2"><i class="bi bi-x-circle-fill text-danger me-2"></i> Marca Personalizada</li>
                            <li><i class="bi bi-x-circle-fill text-danger me-2"></i> API</li>
                        </ul>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted d-block">Suscriptores</small>
                                <strong>45</strong>
                            </div>
                            <span class="badge bg-success">Activo</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between gap-2">
                        <button class="btn btn-outline-primary flex-grow-1 editar-plan-btn" data-plan-id="2"
                            data-bs-toggle="modal" data-bs-target="#editarPlanModal">
                            <i class="bi bi-pencil me-2"></i>
                        </button>
                        <button class="btn btn-outline-danger flex-grow-1" title="Desactivar" data-bs-toggle="modal"
                            data-bs-target="#suspenderPlanModal">
                            <i class="bi bi-person-dash me-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Plan Estándar -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Estándar</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Ideal para restaurantes en crecimiento con múltiples ubicaciones</p>

                        <div class="d-flex align-items-end mb-4">
                            <h2 class="mb-0 fw-bold">$99.99</h2>
                            <span class="text-muted ms-1">/mes</span>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Restaurantes: 3
                            </li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Usuarios: 15</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Almacenamiento: 25
                                GB</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Soporte: Email y
                                Chat</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Analíticas</li>
                            <li class="mb-2"><i class="bi bi-x-circle-fill text-danger me-2"></i> Marca Personalizada</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i> API</li>
                        </ul>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted d-block">Suscriptores</small>
                                <strong>78</strong>
                            </div>
                            <span class="badge bg-success">Activo</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between gap-2">
                        <button class="btn btn-outline-primary flex-grow-1 editar-plan-btn" data-plan-id="2"
                            data-bs-toggle="modal" data-bs-target="#editarPlanModal">
                            <i class="bi bi-pencil me-2"></i>
                        </button>
                        <button class="btn btn-outline-danger flex-grow-1" title="Desactivar" data-bs-toggle="modal"
                            data-bs-target="#suspenderPlanModal">
                            <i class="bi bi-person-dash me-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Plan Premium -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Premium</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Para cadenas de restaurantes que necesitan funcionalidades avanzadas</p>

                        <div class="d-flex align-items-end mb-4">
                            <h2 class="mb-0 fw-bold">$199.99</h2>
                            <span class="text-muted ms-1">/mes</span>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Restaurantes: 10
                            </li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Usuarios: 50</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Almacenamiento: 100
                                GB</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Soporte: 24/7
                                Teléfono y Chat</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Analíticas</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Marca
                                Personalizada
                            </li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i> API</li>
                        </ul>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted d-block">Suscriptores</small>
                                <strong>32</strong>
                            </div>
                            <span class="badge bg-success">Activo</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between gap-2">
                        <button class="btn btn-outline-primary flex-grow-1 editar-plan-btn" data-plan-id="2"
                            data-bs-toggle="modal" data-bs-target="#editarPlanModal">
                            <i class="bi bi-pencil me-2"></i>
                        </button>
                        <button class="btn btn-outline-danger flex-grow-1" title="Desactivar" data-bs-toggle="modal"
                            data-bs-target="#suspenderPlanModal">
                            <i class="bi bi-person-dash me-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Plan Enterprise -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Enterprise</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Solución personalizada para grandes corporaciones</p>

                        <div class="d-flex align-items-end mb-4">
                            <h2 class="mb-0 fw-bold">$499.99</h2>
                            <span class="text-muted ms-1">/mes</span>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Restaurantes:
                                Ilimitados</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Usuarios:
                                Ilimitados</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Almacenamiento:
                                Ilimitado</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Soporte: Gerente
                                de cuenta dedicado</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Analíticas</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Marca
                                Personalizada</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i> API</li>
                        </ul>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted d-block">Suscriptores</small>
                                <strong>8</strong>
                            </div>
                            <span class="badge bg-success">Activo</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between gap-2">
                        <button class="btn btn-outline-primary flex-grow-1 editar-plan-btn" data-plan-id="2"
                            data-bs-toggle="modal" data-bs-target="#editarPlanModal">
                            <i class="bi bi-pencil me-2"></i>
                        </button>
                        <button class="btn btn-outline-danger flex-grow-1" title="Desactivar" data-bs-toggle="modal"
                            data-bs-target="#suspenderPlanModal">
                            <i class="bi bi-person-dash me-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nuevo Plan -->
    <div class="modal fade" id="nuevoPlanModal" tabindex="-1" aria-labelledby="nuevoPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formNuevoPlan" action="#" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoPlanModalLabel">Crear Nuevo Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="nombre_plan" class="form-label">Nombre del Plan</label>
                                <input type="text" class="form-control" id="nombre_plan" name="nombre_plan"
                                    placeholder="Ej: Premium" required>
                            </div>
                            <div class="col-md-6">
                                <label for="precio_mensual" class="form-label">Precio Mensual ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/</span>
                                    <input type="number" class="form-control" id="precio_mensual" name="precio_mensual"
                                        placeholder="0" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Describe las ventajas de este plan" required></textarea>
                            <div class="form-text">Esta descripción se mostrará a los usuarios potenciales</div>
                        </div>

                        <h6 class="mb-3 border-bottom pb-2">Características del Plan</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="num_restaurantes" class="form-label">Número de Restaurantes</label>
                                <input type="number" class="form-control" id="num_restaurantes" name="num_restaurantes"
                                    placeholder="1" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="num_usuarios" class="form-label">Número de Usuarios</label>
                                <input type="number" class="form-control" id="num_usuarios" name="num_usuarios"
                                    placeholder="5" min="1" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="almacenamiento" class="form-label">Almacenamiento</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="almacenamiento" name="almacenamiento"
                                        placeholder="5" min="1" required>
                                    <span class="input-group-text">GB</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_soporte" class="form-label">Tipo de Soporte</label>
                                <select class="form-select" id="tipo_soporte" name="tipo_soporte" required>
                                    <option value="Email">Email</option>
                                    <option value="Email y Chat">Email y Chat</option>
                                    <option value="24/7 Teléfono y Chat">24/7 Teléfono y Chat</option>
                                    <option value="Gerente dedicado">Gerente dedicado</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Características Adicionales</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="analiticas" name="caracteristicas[]"
                                    value="Analíticas Avanzadas">
                                <label class="form-check-label" for="analiticas">Analíticas Avanzadas</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="marca_personalizada"
                                    name="caracteristicas[]" value="Marca Personalizada">
                                <label class="form-check-label" for="marca_personalizada">Marca Personalizada</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="acceso_api" name="caracteristicas[]"
                                    value="Acceso a API">
                                <label class="form-check-label" for="acceso_api">Acceso a API</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="multiples_ubicaciones"
                                    name="caracteristicas[]" value="Múltiples Ubicaciones">
                                <label class="form-check-label" for="multiples_ubicaciones">Múltiples Ubicaciones</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="plan_popular" name="plan_popular"
                                    value="1">
                                <label class="form-check-label" for="plan_popular">Marcar como Plan Popular</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Plan -->
    <div class="modal fade" id="editarPlanModal" tabindex="-1" aria-labelledby="editarPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarPlan" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editar_plan_id" name="id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editarPlanModalLabel">Editar Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Mismo contenido que nuevo plan pero con datos precargados -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="editar_nombre_plan" class="form-label">Nombre del Plan</label>
                                <input type="text" class="form-control" id="editar_nombre_plan" name="nombre_plan"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="editar_precio_mensual" class="form-label">Precio Mensual ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="editar_precio_mensual"
                                        name="precio_mensual" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="editar_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="3" required></textarea>
                        </div>

                        <h6 class="mb-3 border-bottom pb-2">Características del Plan</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editar_num_restaurantes" class="form-label">Número de Restaurantes</label>
                                <input type="number" class="form-control" id="editar_num_restaurantes"
                                    name="num_restaurantes" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editar_num_usuarios" class="form-label">Número de Usuarios</label>
                                <input type="number" class="form-control" id="editar_num_usuarios" name="num_usuarios"
                                    min="1" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="editar_almacenamiento" class="form-label">Almacenamiento</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="editar_almacenamiento"
                                        name="almacenamiento" min="1" required>
                                    <span class="input-group-text">GB</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="editar_tipo_soporte" class="form-label">Tipo de Soporte</label>
                                <select class="form-select" id="editar_tipo_soporte" name="tipo_soporte" required>
                                    <option value="Email">Email</option>
                                    <option value="Email y Chat">Email y Chat</option>
                                    <option value="24/7 Teléfono y Chat">24/7 Teléfono y Chat</option>
                                    <option value="Gerente dedicado">Gerente dedicado</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Características Adicionales</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editar_analiticas"
                                    name="caracteristicas[]" value="Analíticas Avanzadas">
                                <label class="form-check-label" for="editar_analiticas">Analíticas Avanzadas</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editar_marca_personalizada"
                                    name="caracteristicas[]" value="Marca Personalizada">
                                <label class="form-check-label" for="editar_marca_personalizada">Marca
                                    Personalizada</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editar_acceso_api"
                                    name="caracteristicas[]" value="Acceso a API">
                                <label class="form-check-label" for="editar_acceso_api">Acceso a API</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editar_multiples_ubicaciones"
                                    name="caracteristicas[]" value="Múltiples Ubicaciones">
                                <label class="form-check-label" for="editar_multiples_ubicaciones">Múltiples
                                    Ubicaciones</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editar_plan_popular"
                                    name="plan_popular" value="1">
                                <label class="form-check-label" for="editar_plan_popular">Marcar como Plan Popular</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Suspender Plan -->
    <div class="modal fade" id="suspenderPlanModal" tabindex="-1" aria-labelledby="suspenderPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formSuspenderPlan" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="suspender_plan_id" name="id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="suspenderPlanModalLabel">Suspender Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Advertencia:</strong> Esta acción suspenderá el plan seleccionado.
                        </div>
                        <p>¿Estás seguro que deseas suspender el plan <strong id="nombre_plan_suspender"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Suspensión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
