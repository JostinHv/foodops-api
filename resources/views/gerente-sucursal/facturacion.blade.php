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
                <h1 class="h3 mb-0">Gestión de Facturación</h1>
                <p class="mb-0 text-muted">Administra las facturas de tu sucursal</p>
            </div>
            <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevaFacturaModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Factura
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Facturas</div>
                        <h3 class="mb-1">{{ $facturas ? $facturas->count() : 0 }}</h3>
                        <small class="text-muted">Total de facturas emitidas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Órdenes Pendientes</div>
                        <h3 class="mb-1">{{ $ordenesPendientes ? $ordenesPendientes->count() : 0 }}</h3>
                        <small class="text-muted">Por facturar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Pagado</div>
                        <h3 class="mb-1">
                            S/ {{ number_format($facturas ? $facturas->where('estado_pago', 'pagado')->sum('monto_total') : 0, 2) }}</h3>
                        <small class="text-muted">Monto total pagado</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold">Total Pendiente</div>
                        <h3 class="mb-1">
                            S/ {{ number_format($facturas ? $facturas->where('estado_pago', 'pendiente')->sum('monto_total') : 0, 2) }}</h3>
                        <small class="text-muted">Monto por cobrar</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de facturas -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Facturas Emitidas</h5>
                <div>
                    <span class="badge bg-primary me-2">{{ $facturas ? $facturas->count() : 0 }} facturas</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>N° Factura</th>
                            <th>Cliente</th>
                            <th>Mesa</th>
                            <th>Subtotal</th>
                            <th>IGV</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($facturas ?? [] as $factura)
                            <tr>
                                <td>
                                    <strong>{{ $factura->nro_factura }}</strong>
                                    <div class="text-muted small">
                                        {{ $factura->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td>{{ $factura->orden->nombre_cliente }}</td>
                                <td>{{ $factura->orden->mesa->nombre }}</td>
                                <td>S/ {{ number_format($factura->monto_total, 2) }}</td>
                                <td>S/ {{ number_format($factura->monto_total_igv, 2) }}</td>
                                <td>
                                    <strong>S/ {{ number_format($factura->monto_total + $factura->monto_total_igv, 2) }}</strong>
                                </td>
                                <td>
                                        <span
                                            class="badge bg-{{ $factura->estado_pago === 'pagado' ? 'success' : ($factura->estado_pago === 'pendiente' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($factura->estado_pago) }}
                                        </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                                title="Ver detalles"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verFacturaModal"
                                                data-action="ver-factura"
                                                data-factura="{{ $factura->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if($factura->estado_pago === 'pendiente')
                                            <button class="btn btn-sm btn-outline-success"
                                                    title="Marcar como pagado"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editarFacturaModal"
                                                    data-factura="{{ $factura->id }}">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                                        <p class="mb-0">No hay facturas registradas</p>
                                        <small>Genera una nueva factura usando el botón superior</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nueva Factura -->
    <div class="modal fade" id="nuevaFacturaModal" tabindex="-1" aria-labelledby="nuevaFacturaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="nuevaFacturaForm" action="{{ route('gerente.facturacion.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaFacturaModalLabel">Nueva Factura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="orden_id" class="form-label">Orden</label>
                                <select class="form-select" id="orden_id" name="orden_id" required>
                                    <option value="">Seleccione una orden</option>
                                    @foreach($ordenesPendientes ?? [] as $orden)
                                        <option value="{{ $orden->id }}">
                                            Orden #{{ $orden->nro_orden }} - Mesa {{ $orden->mesa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="metodo_pago_id" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago_id" name="metodo_pago_id" required>
                                    <option value="">Seleccione un método</option>
                                    @foreach($metodosPago ?? [] as $metodo)
                                        <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="igv_id" class="form-label">IGV</label>
                                <select class="form-select" id="igv_id" name="igv_id" required>
                                    <option value="{{ $igvActivo->id ?? '' }}">{{ $igvActivo->valor_porcentaje ?? '0' }}%</option>
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
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">Generar Factura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Ver Factura -->
    <div class="modal fade" id="verFacturaModal" tabindex="-1" aria-labelledby="verFacturaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verFacturaModalLabel">Detalles de la Factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6><i class="bi bi-receipt me-1"></i>Número de Factura</h6>
                        <p id="factura-numero"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-person me-1"></i>Cliente</h6>
                        <p id="factura-cliente"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-table me-1"></i>Mesa</h6>
                        <p id="factura-mesa"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-cash me-1"></i>Subtotal</h6>
                        <p id="factura-subtotal"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-percent me-1"></i>IGV</h6>
                        <p id="factura-igv"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-currency-dollar me-1"></i>Total</h6>
                        <p id="factura-total"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-credit-card me-1"></i>Método de Pago</h6>
                        <p id="factura-metodo-pago"></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-toggle-on me-1"></i>Estado</h6>
                        <p id="factura-estado"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnDescargarPDF">
                        <i class="bi bi-file-pdf me-2"></i>Descargar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Factura -->
    <div class="modal fade" id="editarFacturaModal" tabindex="-1" aria-labelledby="editarFacturaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editarFacturaForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarFacturaModalLabel">Actualizar Estado de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_estado_pago" class="form-label">Estado de Pago</label>
                            <select class="form-select" id="edit_estado_pago" name="estado_pago" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="pagado">Pagado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_metodo_pago_id" class="form-label">Método de Pago</label>
                            <select class="form-select" id="edit_metodo_pago_id" name="metodo_pago_id" required>
                                @foreach($metodosPago ?? [] as $metodo)
                                    <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notas" class="form-label">Notas</label>
                            <textarea class="form-control" id="edit_notas" name="notas" rows="2"></textarea>
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

    @push('scripts')
        <script src="{{ asset('js/gerente-sucursal/facturacion.js') }}"></script>
    @endpush
@endsection
