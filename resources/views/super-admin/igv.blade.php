@extends('layouts.app')

@section('title', 'Configuración de IGV')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/igv.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Configuración de IGV</h1>
            </div>
        </div>

        <div class="container py-4">
            <div class="row">
                <div class="col-md-9">
                    <!-- Configuración de Tasas -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Configuración de Tasas</h5>
                            <button class="btn btn-sm btn-primary" id="editarTasaBtn"  data-bs-toggle="modal" data-bs-target="editarIGVModal">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                        </div>
                        <div class="card-body">
                            <form id="formTasaImpuesto" style="display: none;">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pais" class="form-label">País</label>
                                            <select class="form-select" id="pais" name="pais" required>
                                                <option value="Perú" selected>Perú</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tasa_impuesto" class="form-label">Tasa de Impuesto (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="tasa_impuesto"
                                                    name="tasa_impuesto" value="18" min="0" max="100"
                                                    step="0.01" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="cancelarEdicionBtn">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>

                            <div id="vistaTasaImpuesto">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>País:</strong> Perú</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Tasa Actual:</strong> <span id="tasaActual">18</span>%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calculadora de Impuestos -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Calculadora de Impuestos</h5>
                        </div>
                        <div class="card-body">
                            <form id="formCalculadora">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="monto_base" class="form-label">Monto Base</label>
                                        <div class="input-group">
                                            <span class="input-group-text">S/</span>
                                            <input type="number" class="form-control" id="monto_base" placeholder="100"
                                                min="0" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pais_calculadora" class="form-label">País</label>
                                        <select class="form-select" id="pais_calculadora" required>
                                            <option value="Perú" selected>Perú - IGV (<span
                                                    id="tasaCalculadora">18</span>%)</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mb-4" id="calcularBtn">
                                    <i class="bi bi-calculator"></i> Calcular
                                </button>

                                <!-- Resultados -->
                                <div class="card bg-light" id="resultadosCalculo">
                                    <div class="card-body">
                                        <h6 class="card-title border-bottom pb-2">Resultado del Cálculo</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Subtotal:</strong> <span id="subtotal">S/ 0.00</span></p>
                                                <p><strong>Impuesto (<span id="tasaResultado">0</span>%):</strong> <span
                                                        id="impuestoCalculado">S/ 0.00</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="h5"><strong>Total:</strong> <span
                                                        id="totalCalculado">S/ 0.00</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Tasa de IGV -->
<div class="modal fade" id="editarIGVModal" tabindex="-1" aria-labelledby="editarIGVModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarIGV">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarIGVModalLabel">Editar Tasa de IGV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nuevoPorcentajeIGV" class="form-label">Nuevo porcentaje de IGV</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="nuevoPorcentajeIGV" 
                                   value="18" min="0" max="100" step="0.01" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Este cambio afectará todos los cálculos futuros.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarIGVBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/super-admin/igv.js') }}"></script>
@endpush
