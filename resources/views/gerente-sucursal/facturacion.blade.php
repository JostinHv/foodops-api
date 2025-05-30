@extends('layouts.app')

@section('title', 'Facturación')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gerente-sucursal/facturacion.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Facturación</h1>
                <p class="mb-0 text-muted">Gestiona facturas, pagos y reportes financieros</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaFacturaModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Factura
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Ventas del Día</div>
                        <h3 class="mb-1">S/ 3,240.80</h3>
                        <small class="text-success">+18% desde ayer</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Facturas Emitidas</div>
                        <h3 class="mb-1">12</h3>
                        <small class="text-success">+5 en la última hora</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3"">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Pagos Pendientes</div>
                        <h3 class="mb-1">3</h3>
                        <small class="text-muted">S/ 157.08 total</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Facturas del Día</h5>
                <span class="badge bg-primary">4 facturas</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Mesa</th>
                                <th>Subtotal</th>
                                <th>IGV</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Factura 1 -->
                            <tr>
                                <td><strong>FAC-001</strong></td>
                                <td>Juan Pérez</td>
                                <td>Mesa 5</td>
                                <td>S/ 72.50</td>
                                <td>S/ 13.05</td>
                                <td class="fw-bold">S/ 85.55</td>
                                <td><span class="badge bg-success">Pagada</span></td>
                                <td><span class="badge bg-info text-dark">Tarjeta</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Reimprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Anular"  data-bs-toggle="modal" data-bs-target="#eliminarItemModal">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Factura 2 -->
                            <tr>
                                <td><strong>FAC-002</strong></td>
                                <td>María García</td>
                                <td>Mesa 2</td>
                                <td>S/ 38.30</td>
                                <td>S/ 6.89</td>
                                <td class="fw-bold">S/ 45.19</td>
                                <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                                <td><span class="badge bg-success">Efectivo</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Reimprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" title="Marcar como pagada">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Factura 3 -->
                            <tr>
                                <td><strong>FAC-003</strong></td>
                                <td>Carlos López</td>
                                <td>Mesa 8</td>
                                <td>S/ 107.00</td>
                                <td>S/ 19.26</td>
                                <td class="fw-bold">S/ 126.26</td>
                                <td><span class="badge bg-success">Pagada</span></td>
                                <td><span class="badge bg-info text-dark">Tarjeta</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Reimprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Anular"  data-bs-toggle="modal" data-bs-target="#eliminarItemModal">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Factura 4 -->
                            <tr>
                                <td><strong>FAC-004</strong></td>
                                <td>Ana Martín</td>
                                <td>Mesa 3</td>
                                <td>S/ 24.25</td>
                                <td>S/ 4.37</td>
                                <td class="fw-bold">S/ 28.62</td>
                                <td><span class="badge bg-secondary">Cancelada</span></td>
                                <td><span class="badge bg-success">Efectivo</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Reimprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-dark" disabled title="Factura cancelada">
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Filtros y resumen -->
                <div class="row mt-4">
                    <div class="col-md-6 text-end">
                        <div class="d-inline-block bg-light p-2 rounded">
                            <strong>Total del día:</strong> <span class="text-success">S/ 285.62</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal: Nueva Factura -->
    <div class="modal fade" id="nuevaFacturaModal" tabindex="-1" aria-labelledby="nuevaFacturaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="nuevaFacturaForm" action="#" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaFacturaModalLabel">Nueva Factura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">Genera una nueva factura para un cliente</p>

                        <!-- Información del Cliente -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label for="cliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="cliente" name="cliente"
                                    placeholder="Nombre del cliente" required>
                            </div>
                            <div class="col-md-4">
                                <label for="mesa" class="form-label">Mesa</label>
                                <select class="form-select" id="mesa" name="mesa" required>
                                    <option value="" selected disabled>Seleccionar mesa</option>
                                    <option value="1">Mesa 1</option>
                                    <option value="2">Mesa 2</option>
                                    <option value="3">Mesa 3</option>
                                    <option value="4">Mesa 4</option>
                                    <option value="5">Mesa 5</option>
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label for="ruc" class="form-label">RUC (Opcional)</label>
                                <input type="text" class="form-control" id="ruc" name="ruc"
                                    placeholder="20123456789">
                            </div>
                            <div class="col-md-4">
                                <label for="metodo_pago" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                    <option value="" selected disabled>Seleccionar método</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Resumen de la Orden -->
                        <h6 class="mb-3">Resumen de la Orden</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm">
                                <tbody id="itemsFactura">
                                    <!-- Ejemplo de items (se llenará dinámicamente) -->
                                    <tr>
                                        <td>Lomo Saltado x1</td>
                                        <td class="text-end">S/ 28.50</td>
                                    </tr>
                                    <tr>
                                        <td>Pisco Sour x2</td>
                                        <td class="text-end">S/ 30.00</td>
                                    </tr>
                                    <tr>
                                        <td>Suspiro Limeño x1</td>
                                        <td class="text-end">S/ 12.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div class="row justify-content-end">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="text-end">Subtotal:</td>
                                            <td class="text-end">S/ 70.50</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">IGV (18%):</td>
                                            <td class="text-end">S/ 12.69</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold">S/ 83.19</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Generar Factura</button>
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
                <p>¿Estás seguro que deseas anular esta factura?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminarItem" action="#" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Anular Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
