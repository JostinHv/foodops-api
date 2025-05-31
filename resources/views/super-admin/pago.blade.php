@extends('layouts.app')

@section('title', 'Métodos de Pago')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/pago.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Métodos de Pago</h1>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoMetodoPagoModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Plan
            </a>
        </div>

        <div class="row g-4">
            <!-- Tarjeta Visa/Mastercard -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Visa/Mastercard</h5>
                            <small class="text-muted">Stripe - Tarjetas</small>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-globe me-2"></i>
                            <small>Disponible en 5 países</small>
                        </div>

                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>Comisión:</strong> 2.8% + USD 0.3</li>
                            <li class="mb-2"><strong>Procesamiento:</strong> Inmediato</li>
                            <li class="mb-2"><strong>Transacciones:</strong> 1,250</li>
                            <li><strong>Volumen:</strong> $125,000</li>
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#editarMetodoPagoModal">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#desactivarModal">
                            <i class="bi bi-power"></i> Desactivar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tarjeta PayPal -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">PayPal</h5>
                            <small class="text-muted">PayPal - Billetes</small>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-globe me-2"></i>
                            <small>Disponible en 7 países</small>
                        </div>

                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>Comisión:</strong> 3.4% + USD 0.3</li>
                            <li class="mb-2"><strong>Procesamiento:</strong> 1-3 días hábiles</li>
                            <li class="mb-2"><strong>Transacciones:</strong> 890</li>
                            <li><strong>Volumen:</strong> $89,000</li>
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#editarMetodoModal">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#desactivarMetodoModal">
                            <i class="bi bi-power"></i> Desactivar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transferencia Bancaria -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Transferencia Bancaria</h5>
                            <small class="text-muted">Culqi - Transferencia</small>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-globe me-2"></i>
                            <small>Disponible en 1 país</small>
                        </div>

                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>Comisión:</strong> 1.5% + PEN 2</li>
                            <li class="mb-2"><strong>Procesamiento:</strong> 1-2 días hábiles</li>
                            <li class="mb-2"><strong>Transacciones:</strong> 456</li>
                            <li><strong>Volumen:</strong> $45,600</li>
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#editarMetodoModal">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#desactivarMetodoModal">
                            <i class="bi bi-power"></i> Desactivar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nuevo Método de Pago -->
    <div class="modal fade" id="nuevoMetodoPagoModal" tabindex="-1" aria-labelledby="nuevoMetodoPagoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formNuevoMetodoPago" action="#" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoMetodoPagoModalLabel">Agregar Método de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">Nombre del Método</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="nombre_metodo" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre_metodo" name="nombre_metodo"
                                    placeholder="Ej: Visa/Mastercard" required>
                            </div>
                            <div class="col-md-6">
                                <label for="proveedor" class="form-label">Proveedor</label>
                                <select class="form-select" id="proveedor" name="proveedor" required>
                                    <option selected disabled>Selecciona un proveedor</option>
                                    <option value="Stripe">Stripe</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Culqi">Culqi</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="mb-3">Tipo de Método</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="tipo_metodo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo_metodo" name="tipo_metodo" required>
                                    <option selected disabled>Selecciona un tipo</option>
                                    <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                    <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                                    <option value="Billetera Digital">Billetera Digital</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Comisiones</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="comision_porcentaje"
                                        name="comision_porcentaje" placeholder="0" min="0" max="100"
                                        step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3">Tiempo de Procesamiento</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tiempo_procesamiento" class="form-label">Tiempo</label>
                                <select class="form-select" id="tiempo_procesamiento" name="tiempo_procesamiento"
                                    required>
                                    <option selected disabled>Selecciona un tiempo</option>
                                    <option value="Inmediato">Inmediato</option>
                                    <option value="1-2 días hábiles">1-2 días hábiles</option>
                                    <option value="1-3 días hábiles">1-3 días hábiles</option>
                                    <option value="3-5 días hábiles">3-5 días hábiles</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="moneda" class="form-label">Moneda Principal</label>
                                <select class="form-select" id="moneda" name="moneda" required>
                                    <option value="USD">USD (Dólares Americanos)</option>
                                    <option value="EUR">EUR (Euros)</option>
                                    <option value="PEN">PEN (Soles Peruanos)</option>
                                    <option value="MXN">MXN (Pesos Mexicanos)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Método</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Método de Pago -->
    <div class="modal fade" id="editarMetodoPagoModal" tabindex="-1" aria-labelledby="editarMetodoPagoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarMetodoPago" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editar_metodo_id" name="id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editarMetodoPagoModalLabel">Editar Método de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">Nombre del Método</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="editar_nombre_metodo" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="editar_nombre_metodo"
                                    name="nombre_metodo" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editar_proveedor" class="form-label">Proveedor</label>
                                <select class="form-select" id="editar_proveedor" name="proveedor" required>
                                    <option value="Stripe">Stripe</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Culqi">Culqi</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="mb-3">Tipo de Método</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="editar_tipo_metodo" class="form-label">Tipo</label>
                                <select class="form-select" id="editar_tipo_metodo" name="tipo_metodo" required>
                                    <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                    <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                                    <option value="Billetera Digital">Billetera Digital</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Comisiones</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="editar_comision_porcentaje"
                                        name="comision_porcentaje" min="0" max="100" step="0.01"
                                        required>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3">Tiempo de Procesamiento</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editar_tiempo_procesamiento" class="form-label">Tiempo</label>
                                <select class="form-select" id="editar_tiempo_procesamiento" name="tiempo_procesamiento"
                                    required>
                                    <option value="Inmediato">Inmediato</option>
                                    <option value="1-2 días hábiles">1-2 días hábiles</option>
                                    <option value="1-3 días hábiles">1-3 días hábiles</option>
                                    <option value="3-5 días hábiles">3-5 días hábiles</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editar_moneda" class="form-label">Moneda Principal</label>
                                <select class="form-select" id="editar_moneda" name="moneda" required>
                                    <option value="USD">USD (Dólares Americanos)</option>
                                    <option value="EUR">EUR (Euros)</option>
                                    <option value="PEN">PEN (Soles Peruanos)</option>
                                    <option value="MXN">MXN (Pesos Mexicanos)</option>
                                </select>
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

    <!-- Modal: Desactivar pago -->
    <div class="modal fade" id="desactivarModal" tabindex="-1" aria-labelledby="suspenderPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formSuspenderPlan" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="suspender_plan_id" name="id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="suspenderPlanModalLabel">Desactivar Método de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Advertencia:</strong> Esta acción desactivará el método de pago seleccionado de manera
                            permanente.
                        </div>
                        <p>¿Estás seguro que deseas desactivar el método de pago <strong
                                id="nombre_plan_suspender"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
