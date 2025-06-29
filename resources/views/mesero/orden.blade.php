@extends('layouts.app')

@section('title', 'Órdenes del Mesero')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mesero/orden.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Órdenes</h2>
                <p class="text-muted mb-0">Gestiona las órdenes de tus mesas</p>
            </div>
            <a href="{{ route('mesero.orden.store') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nueva Orden
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
                            @foreach($estadosOrden as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
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

        <!-- Lista de órdenes -->
        <div class="row g-4" id="lista-ordenes">
            @forelse ($ordenes as $orden)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 orden-card"
                         data-orden-id="{{ $orden->id }}"
                         data-estado-id="{{ $orden->estadoOrden->id }}"
                         data-fecha="{{ $orden->created_at->locale('es')->isoFormat('LLLL') }}" data-bs-toggle="modal">
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
                                <span class="badge bg-{{ $orden->estadoOrden->nombre === 'Pendiente' ? 'danger' :
                                                      ($orden->estadoOrden->nombre === 'En Proceso' ? 'warning' : 'success') }}">
                                    {{ $orden->estadoOrden->nombre }}
                                </span>
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
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cart me-2 text-primary"></i>
                                    <span>{{ $orden->itemsOrdenes->count() }} productos</span>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Total:
                                        S/. {{ number_format($orden->itemsOrdenes->sum('monto'), 2) }}</h6>
                                    <button class="btn btn-outline-primary btn-sm ver-detalles d-none d-md-block"
                                            data-orden-id="{{ $orden->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleOrdenModal">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
                                    </button>
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

    <!-- Modal de Detalles de Orden -->
    <div class="modal fade" id="detalleOrdenModal" tabindex="-1" aria-labelledby="detalleOrdenModalLabel"
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
                            <i class="bi bi-check-circle me-2"></i>Marcar como Servida
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/mesero/orden.js') }}"></script>
@endpush
