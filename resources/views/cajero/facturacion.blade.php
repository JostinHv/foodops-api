@extends('layouts.app')

@section('title', 'Facturación')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cajero/facturacion.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Facturación</h1>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#facturarModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Facturación
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Card de órdenes para facturar -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Órdenes para Facturar</h5>
                <span class="badge bg-primary">5 órdenes encontradas</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Orden #</th>
                                <th>Mesa</th>
                                <th>Mesero</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Orden #101 -->
                            <tr>
                                <td><strong>#101</strong></td>
                                <td>Mesa 6</td>
                                <td>Mesero 2</td>
                                <td>$25.50</td>
                                <td>
                                    <span class="badge bg-success">Servida</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success" title="Facturar" data-bs-toggle="modal"
                                            data-bs-target="#facturarModal" data-order-id="101">
                                            <i class="bi bi-receipt"></i> Facturar
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Anular" data-bs-toggle="modal"
                                            data-bs-target="#anularModal" data-order-id="101">
                                            <i class="bi bi-x-circle"></i> Anular
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Orden #102 -->
                            <tr>
                                <td><strong>#102</strong></td>
                                <td>Mesa 7</td>
                                <td>Mesero 3</td>
                                <td>$51.00</td>
                                <td>
                                    <span class="badge bg-info">Lista</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success" title="Facturar" data-bs-toggle="modal"
                                            data-bs-target="#facturarModal" data-order-id="101">
                                            <i class="bi bi-receipt"></i> Facturar
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Anular" data-bs-toggle="modal"
                                            data-bs-target="#anularModal" data-order-id="101">
                                            <i class="bi bi-x-circle"></i> Anular
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Orden #103 -->
                            <tr>
                                <td><strong>#103</strong></td>
                                <td>Mesa 8</td>
                                <td>Mesero 1</td>
                                <td>$76.50</td>
                                <td>
                                    <span class="badge bg-success">Servida</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success" title="Facturar" data-bs-toggle="modal"
                                            data-bs-target="#facturarModal" data-order-id="101">
                                            <i class="bi bi-receipt"></i> Facturar
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Anular" data-bs-toggle="modal"
                                            data-bs-target="#anularModal" data-order-id="101">
                                            <i class="bi bi-x-circle"></i> Anular
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

    <!-- Modal para Facturar -->
    <div class="modal fade" id="facturarModal" tabindex="-1" aria-labelledby="facturarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="facturarModalLabel">Facturar Orden #<span id="orderNumber"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Mesa:</strong> <span id="mesaInfo"></span></p>
                            <p><strong>Mesero:</strong> <span id="meseroInfo"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total:</strong> <span id="totalInfo"></span></p>
                            <p><strong>Estado:</strong> <span id="estadoInfo" class="badge"></span></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tipoPago" class="form-label">Tipo de Pago</label>
                        <select class="form-select" id="tipoPago">
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comentarios" class="form-label">Comentarios</label>
                        <textarea class="form-control" id="comentarios" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarFactura">Generar Factura</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Anular Factura -->
<div class="modal fade" id="anularModal" tabindex="-1" aria-labelledby="anularModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header >
                <h5 class="modal-title" id="anularModalLabel">Anular Orden #<span id="anularOrderNumber"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas anular esta orden?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i> Esta acción no se puede deshacer.
                </div>
                <div class="mb-3">
                    <label for="motivoAnulacion" class="form-label">Motivo de anulación</label>
                    <textarea class="form-control" id="motivoAnulacion" rows="2" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarAnulacion">Confirmar Anulación</button>
            </div>
        </div>
    </div>
</div>
@endsection
