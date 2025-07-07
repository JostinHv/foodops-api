@extends('layouts.app')

@section('title', 'Facturación')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cajero/facturacion.css') }}">
@endpush

@section('content')
    <!-- Meta tags para Pusher y datos del usuario -->
    <meta name="tenant-id" content="{{ auth()->user()->tenant_id }}">
    <meta name="sucursal-id" content="{{ auth()->user()->asignacionPersonal->sucursal_id}}">
    <meta name="pusher-app-key" content="{{ config('broadcasting.connections.pusher.key') }}">
    <meta name="pusher-app-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Facturación</h2>
                <p class="text-muted mb-0">Gestiona órdenes y genera comprobantes de pago</p>
            </div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facturarModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Comprobante
            </a>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold text-primary">Órdenes Pendientes</div>
                        <h3 class="mb-1">{{ $ordenes->whereNotIn('estado_orden_id', [6])->count() }}</h3>
                        <small class="text-muted">Por facturar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold text-success">Facturas Hoy</div>
                        <h3 class="mb-1">{{ $facturas->where('created_at', '>=', now()->startOfDay())->count() }}</h3>
                        <small class="text-muted">Generadas hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold text-info">Total Facturado</div>
                        <h3 class="mb-1">S/ {{ number_format($facturas->sum('monto_total_igv'), 2) }}</h3>
                        <small class="text-muted">Monto total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="fw-bold text-warning">Órdenes Servidas</div>
                        <h3 class="mb-1">{{ $ordenes->where('estado_orden_id', 4)->count() }}</h3>
                        <small class="text-muted">Listas para cobrar</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas principales -->
        <ul class="nav nav-tabs mb-4" id="facturacionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ordenes-tab" data-bs-toggle="tab" data-bs-target="#ordenes"
                        type="button" role="tab">
                    <i class="bi bi-list-ul me-2"></i>Órdenes ({{ $ordenes->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="facturas-tab" data-bs-toggle="tab" data-bs-target="#facturas" type="button"
                        role="tab">
                    <i class="bi bi-receipt me-2"></i>Facturas Recientes ({{ $facturas->count() }})
                </button>
            </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="facturacionTabsContent">
            <!-- Pestaña de Órdenes -->
            <div class="tab-pane fade show active" id="ordenes" role="tabpanel">
        <!-- Filtros y búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                            <div class="col-md-8">
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
                                    @foreach($estadosOrden as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

                <!-- Lista de órdenes -->
        <div class="row g-4" id="lista-ordenes">
                    @forelse ($ordenes as $orden)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 orden-card"
                                 data-orden-id="{{ $orden->id }}"
                                 data-estado-id="{{ $orden->estadoOrden->id }}"
                                 data-fecha="{{ $orden->created_at->locale('es')->isoFormat('LLLL') }}"
                            >
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                            <h5 class="card-title mb-1">Orden #{{ $orden->nro_orden }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-clock me-1"></i>
                                                {{ $orden->tiempo_transcurrido['humano'] }}
                                                @if($orden->tiempo_transcurrido['es_hoy'])
                                                    ({{ $orden->tiempo_transcurrido['minutos'] }} min)
                                                @endif
                                    </p>
                                </div>
                                        <x-estado-orden-badge :estado="$orden->estadoOrden" />
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-table me-2 text-primary"></i>
                                            <span>Mesa {{ $orden->mesa->nombre }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person me-2 text-primary"></i>
                                            <span>{{ $orden->nombre_cliente ?: 'Sin especificar' }}</span>
                                </div>
                                        <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-cart me-2 text-primary"></i>
                                            <span>{{ $orden->itemsOrdenes->count() }} productos</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-wallet2 me-2 text-primary"></i> 
                                            <span>{{ $orden->estadoOrden->nombre === 'Pagada' ? 'Pagado' : 'Pendiente' }}</span>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Total:
                                                S/. {{ number_format($orden->itemsOrdenes->sum('monto'), 2) }}</h6>
                                            <div class="btn-group">
                                                <button
                                                    class="btn btn-outline-primary btn-sm ver-detalles d-none d-md-block"
                                                    data-orden-id="{{ $orden->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleComprobanteModal">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
                                    </button>
                                                @if(in_array($orden->estado_orden_id, [1, 2, 3, 4, 5, 7, 8, 9, 10], true))
                                                    <button class="btn btn-outline-success btn-sm facturar-orden"
                                                            data-orden-id="{{ $orden->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#facturarModal">
                                                        <i class="bi bi-receipt me-1"></i>Facturar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay órdenes disponibles en este momento.
                            </div>
                        </div>
                    @endforelse
                    </div>
                </div>
            
            <!-- Pestaña de Facturas -->
            <div class="tab-pane fade" id="facturas" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Facturas Recientes</h5>
                        <div>
                            <span class="badge bg-primary me-2">{{ $facturas->count() }} facturas</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>N° Factura</th>
                                    <th>Orden</th>
                                    <th>Cliente</th>
                                    <th>Mesa</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($facturas as $factura)
                                    <tr>
                                        <td>
                                            <strong class="factura-numero">
                                                {{ $factura->nro_factura }}
                                            </strong>
                                            <div class="text-muted small">
                                                {{ $factura->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="orden-numero">
                                                #{{ $factura->orden->nro_orden }}
                                            </span>
                                        </td>
                                        <td>{{ $factura->orden->nombre_cliente ?: 'Cliente General' }}</td>
                                        <td>{{ $factura->orden->mesa->nombre }}</td>
                                        <td>
                                            <strong>S/ {{ number_format($factura->monto_total_igv, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $factura->estado_pago === 'pagado' ? 'success' : ($factura->estado_pago === 'pendiente' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($factura->estado_pago) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-primary ver-factura"
                                                        title="Ver detalles"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#verFacturaModal"
                                                        data-factura="{{ $factura->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="{{ route('cajero.facturacion.pdf', $factura->id) }}"
                                                   class="btn btn-sm btn-outline-secondary" title="Descargar PDF"
                                                   target="_blank">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('cajero.facturacion.pdf-pos', $factura->id) }}"
                                                   class="btn btn-sm btn-outline-info" title="Imprimir Ticket"
                                                   target="_blank">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
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
        </div>
    </div>

    <!-- Modal de Detalles de Comprobante -->
    <div class="modal fade" id="detalleComprobanteModal" tabindex="-1" aria-labelledby="detalleOrdenModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleOrdenModalLabel">
                        <i class="bi bi-receipt me-2"></i>
                        Detalles de la Orden
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
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Estado y Tiempo</h6>
                            <div id="orden-estado-tiempo">
                                <!-- Se llenará con JavaScript -->
                            </div>
                            <div id="bloque-cambiar-estado">
                                <form id="formCambiarEstado" class="mt-3">
                                    @csrf
                                    <div class="d-flex flex-column gap-2">
                                        <label for="estado_orden_id" class="form-label mb-0">
                                            <i class="bi bi-arrow-repeat me-1"></i>Cambiar Estado
                                        </label>
                                        <div class="d-flex gap-2">
                                            <select class="form-select" id="estado_orden_id" name="estado_orden_id">
                                                @foreach($estadosOrden as $estado)
                                                    <option value="{{ $estado->id }}"
                                                            data-color="{{
                                                                        $estado->nombre === 'En Proceso' ? 'warning' :
                                                                        ($estado->nombre === 'Preparada' ? 'info' :
                                                                        ($estado->nombre === 'Cancelada' ? 'danger' :
                                                                        ($estado->nombre === 'Servida' ? 'success' :
                                                                        ($estado->nombre === 'Solicitando Pago' ? 'primary' :
                                                                        ($estado->nombre === 'Pagada' ? 'success' :
                                                                        ($estado->nombre === 'En disputa' ? 'danger' : 'secondary'))))))
                                                                        }}">
                                                        {{ $estado->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-1"></i>Actualizar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div id="msg-cambiar-estado-pagada" class="alert alert-warning mt-2 d-none" role="alert">
                                    <i class="bi bi-lock me-1"></i> No se puede modificar el estado de una orden pagada.
                                </div>
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
                        <h5 class="modal-title" id="facturarModalLabel">Generar Comprobante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="orden_id" class="form-label">Orden</label>
                                <select class="form-select" id="orden_id" name="orden_id" required>
                                    <option value="">Seleccione una orden</option>
                                    @foreach($ordenesPendientes as $orden)
                                        <option value="{{ $orden->id }}"
                                                data-total="{{ $orden->itemsOrdenes->sum('monto') }}">
                                            Orden #{{ $orden->nro_orden }} - Mesa {{ $orden->mesa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="metodo_pago_id" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago_id" name="metodo_pago_id" required>
                                    <option value="">Seleccione un método</option>
                                    @foreach($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="igv_id" class="form-label">IGV</label>
                                <select class="form-select" id="igv_id" name="igv_id" required>
                                    <option value="{{ $igvActivo->id ?? '' }}">{{ $igvActivo->valor_porcentaje ?? '0' }}
                                        %
                                    </option>
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

    <!-- Modal para Ver Factura -->
    <div class="modal fade" id="verFacturaModal" tabindex="-1" aria-labelledby="verFacturaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="verFacturaModalLabel">
                        <i class="bi bi-receipt me-2"></i>Detalles de la Factura
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="factura-detalles-contenido">
                        <!-- Placeholder de carga inicial -->
                        <div class="text-center py-5" id="loading-placeholder">
                            <div class="spinner-border text-primary mb-3" role="status"
                                 style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <h6 class="text-muted">Cargando detalles de la factura...</h6>
                            <p class="text-muted small">Por favor espera un momento</p>
                        </div>

                        <!-- Estructura para mostrar los detalles (inicialmente oculta) -->
                        <div id="factura-loaded-content" style="display: none;">
                            <!-- Header con información principal -->
                            <div class="bg-light p-4 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-receipt text-primary fs-4 me-3"></i>
                                            <div>
                                                <h4 class="mb-0 text-primary" id="detalle-factura-numero">#F-00001</h4>
                                                <small class="text-muted" id="detalle-factura-fecha">01/01/2024
                                                    12:00</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <div class="d-flex flex-column align-items-md-end">
                                            <span class="badge bg-success fs-6 px-3 py-2 mb-2" id="detalle-estado-pago">Pagado</span>
                                            <small class="text-muted">Estado de Pago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <!-- Información de la Orden -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-bottom">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-journal-text text-primary me-2"></i>
                                                    Información de la Orden
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-3 col-sm-6">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Número de Orden</small>
                                                            <span class="fw-bold text-primary fs-5"
                                                                  id="detalle-orden-numero">#001</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Cliente</small>
                                                            <span class="fw-semibold" id="detalle-orden-cliente">Cliente General</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Mesa</small>
                                                            <span class="fw-semibold"
                                                                  id="detalle-orden-mesa">Mesa 1</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Fecha/Hora Orden</small>
                                                            <span class="fw-semibold" id="detalle-orden-fecha">01/01/2024 12:00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Items de la Orden -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-bottom">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-list-ul text-primary me-2"></i>
                                                    Items de la Orden
                                                </h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th class="ps-3">Producto</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-end">Precio Unit.</th>
                                                            <th class="text-end pe-3">Subtotal</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="detalle-items-list">
                                                        <!-- Items se cargarán aquí por JS -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumen de Totales -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-bottom">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-cash text-primary me-2"></i>
                                                    Resumen de Totales
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Subtotal:</span>
                                                            <span class="fw-semibold">S/ <span id="detalle-subtotal">0.00</span></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">IGV (<span
                                                                    id="detalle-igv-porcentaje">0</span>%):</span>
                                                            <span class="fw-semibold">S/ <span
                                                                    id="detalle-igv">0.00</span></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <hr class="my-2">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="fw-bold fs-5">Total:</span>
                                                            <span class="fw-bold fs-5 text-success">S/ <span
                                                                    id="detalle-total">0.00</span></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información de Pago -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-bottom">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-credit-card text-primary me-2"></i>
                                                    Información de Pago
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Método de Pago</small>
                                                            <span class="fw-semibold"
                                                                  id="detalle-metodo-pago">Efectivo</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Notas</small>
                                                            <span class="fw-semibold" id="detalle-notas">Ninguna</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cerrar
                    </button>
                    <div class="btn-group">
                        <a href="#" class="btn btn-primary" id="btnDescargarPDF">
                            <i class="bi bi-file-pdf me-2"></i>Descargar Factura
                        </a>
                        <a href="#" class="btn btn-info" id="btnImprimirPOS">
                            <i class="bi bi-printer me-2"></i>Imprimir Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
    <script src="{{ asset('js/cajero/facturacion.js') }}"></script>
@endpush
