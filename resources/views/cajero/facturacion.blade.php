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
                <h2 class="mb-1">Facturación</h2>
                <p class="text-muted mb-0">Gestiona boletas, facturas y pagos</p>
            </div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facturarModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Comprobante
            </a>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="buscarOrden" class="form-control"
                                   placeholder="Buscar por mesa o cliente...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filtroEstado">
                            <option value="">Todos los estados</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="ordenarPor">
                            <option value="reciente">Más recientes</option>
                            <option value="antiguo">Más antiguas</option>
                            <option value="mesa">Por mesa</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de facturas -->
        <div class="row g-4" id="lista-ordenes">
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 orden-card" data-bs-toggle="modal">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Comprobante #</h5>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-clock me-1"></i>
                                        
                                    </p>
                                </div>
                                <span class="badge bg">
                                </span>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-table me-2 text-primary"></i>
                                    <span>Mesa </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person me-2 text-primary"></i>
                                    <span></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cart me-2 text-primary"></i>
                                    <span>productos</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-wallet2 me-2 text-primary"></i> 
                                    <span>pago</span>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Total:
                                        S/. </h6>
                                    <button class="btn btn-outline-primary btn-sm ver-detalles d-none d-md-block"
                                            data-orden-id=""
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleComprobanteModal">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay órdenes disponibles en este momento.
                    </div>
                </div>
            
        </div>
    </div>

    <!-- Modal de Detalles de Comprobante -->
    <div class="modal fade" id="detalleComprobanteModal" tabindex="-1" aria-labelledby="detalleOrdenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleOrdenModalLabel">
                        <i class="bi bi-receipt me-2"></i>
                        Detalles de Comprobante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Información General</h6>
                            <div id="orden-info-general">
                                <!-- Se llenará con JavaScript -->
                            </div>
                        </div>

                        
                    </div>

                    <h6 class="border-bottom pb-2">Productos</h6>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla-productos">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Se llenará con JavaScript -->
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end" id="orden-total"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <form action="" method="POST" class="d-inline" id="formMarcarServida">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Procesar Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Comprobante -->
    <div class="modal fade" id="facturarModal" tabindex="-1" aria-labelledby="facturarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="facturarForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="facturarModalLabel">Comprobante #<span id="orderNumber"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="orden_id" class="form-label">Orden</label>
                                <select class="form-select" id="orden_id" name="orden_id" required>
                                    <option value="">Seleccione una orden</option>
                                        <option value="">
                                            Orden  - Mesa 
                                        </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_id" class="form-label">Tipo de Comprobante</label>
                                <select class="form-select" id="tipo_id" name="tipo_id" required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="metodo_pago_id" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago_id" name="metodo_pago_id" required>
                                    <option value="">Seleccione un método</option>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="igv_id" class="form-label">IGV</label>
                                <select class="form-select" id="igv_id" name="igv_id" required>
                                    <option value="">
                                        %
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="estado_pago" class="form-label">Estado de Pago</label>
                                <select class="form-select" id="estado_pago" name="estado_pago" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="pagado">Pagado</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="notas" class="form-label">Notas</label>
                                <textarea class="form-control" id="notas" name="notas" rows="2"></textarea>
                            </div>
                            <!-- Resumen de totales -->
                            <div class="col-12 mt-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6 class="mb-2">Subtotal</h6>
                                                <h4 id="subtotal">S/ 0.00</h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-2">IGV (<span id="igv_porcentaje">0</span>%)</h6>
                                                <h4 id="monto_igv">S/ 0.00</h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-2">Total</h6>
                                                <h4 id="total">S/ 0.00</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmarFactura">Generar Comprobante</button>
                    </div>
                </form>
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
