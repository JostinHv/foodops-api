@extends('layouts.app')

@section('title', 'Órdenes de Cocina')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mesero/orden.css') }}">
    <style>
        .estado-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .acceso-rapido {
            transition: all 0.3s ease;
        }
        .acceso-rapido:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .orden-pendiente {
            border-left: 4px solid #dc3545;
        }
        .orden-proceso {
            border-left: 4px solid #ffc107;
        }
        .orden-preparada {
            border-left: 4px solid #17a2b8;
        }
        .tiempo-urgente {
            color: #dc3545;
            font-weight: bold;
        }
        .tiempo-normal {
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <!-- Meta tags para Pusher y datos del usuario -->
    <meta name="tenant-id" content="{{ auth()->user()->tenant_id }}">
    <meta name="sucursal-id" content="{{ auth()->user()->asignacionPersonal->sucursal_id}}">
    <meta name="pusher-app-key" content="{{ config('broadcasting.connections.pusher.key') }}">
    <meta name="pusher-app-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <!-- Header con estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Cocina</h2>
                        <p class="text-muted mb-0">Gestión de órdenes para preparación</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos para cocinero -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="bi bi-lightning me-2"></i>Accesos Rápidos
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="acceso-rapido card text-center p-3 bg-danger bg-opacity-10 border-danger">
                                    <div class="card-body">
                                        <i class="bi bi-exclamation-triangle text-danger fs-1 mb-2"></i>
                                        <h6 class="card-title text-danger">Pendientes</h6>
                                        <p class="card-text small">Órdenes esperando preparación</p>
                                        <button class="btn btn-sm btn-danger" onclick="filtrarPorEstado('Pendiente')">
                                            Ver Pendientes
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="acceso-rapido card text-center p-3 bg-warning bg-opacity-10 border-warning">
                                    <div class="card-body">
                                        <i class="bi bi-clock text-warning fs-1 mb-2"></i>
                                        <h6 class="card-title text-warning">En Proceso</h6>
                                        <p class="card-text small">Órdenes en preparación</p>
                                        <button class="btn btn-sm btn-warning" onclick="filtrarPorEstado('En Proceso')">
                                            Ver En Proceso
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="acceso-rapido card text-center p-3 bg-info bg-opacity-10 border-info">
                                    <div class="card-body">
                                        <i class="bi bi-check-circle text-info fs-1 mb-2"></i>
                                        <h6 class="card-title text-info">Preparadas</h6>
                                        <p class="card-text small">Órdenes listas para servir</p>
                                        <button class="btn btn-sm btn-info" onclick="filtrarPorEstado('Preparada')">
                                            Ver Preparadas
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="acceso-rapido card text-center p-3 bg-success bg-opacity-10 border-success">
                                    <div class="card-body">
                                        <i class="bi bi-list-check text-success fs-1 mb-2"></i>
                                        <h6 class="card-title text-success">Todas</h6>
                                        <p class="card-text small">Ver todas las órdenes</p>
                                        <button class="btn btn-sm btn-success" onclick="filtrarPorEstado('')">
                                            Ver Todas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros avanzados para cocinero -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-calendar"></i>
                            </span>
                            <input type="date" id="filtroFecha" class="form-control"
                                   value="{{ $fechaSeleccionada ?? date('Y-m-d') }}"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="buscarOrden" class="form-control"
                                   placeholder="Buscar por mesa o cliente...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filtroEstado">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente" selected>Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Preparada">Preparada</option>
                            <option value="Servida">Servida</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="ordenarPor">
                            <option value="urgente">Más urgentes</option>
                            <option value="reciente">Más recientes</option>
                            <option value="antiguo">Más antiguas</option>
                            <option value="mesa">Por mesa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filtroTiempo">
                            <option value="">Cualquier tiempo</option>
                            <option value="5">Más de 5 min</option>
                            <option value="10">Más de 10 min</option>
                            <option value="15">Más de 15 min</option>
                            <option value="20">Más de 20 min</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de órdenes -->
        <div class="row g-4" id="lista-ordenes">
            @forelse ($ordenes as $orden)
                @php
                    $claseOrden = '';
                    $claseTiempo = '';
                    if ($orden->estadoOrden->nombre === 'Pendiente') {
                        $claseOrden = 'orden-pendiente';
                        $claseTiempo = $orden->tiempo_transcurrido['minutos'] > 10 ? 'tiempo-urgente' : 'tiempo-normal';
                    } elseif ($orden->estadoOrden->nombre === 'En Proceso') {
                        $claseOrden = 'orden-proceso';
                        $claseTiempo = $orden->tiempo_transcurrido['minutos'] > 15 ? 'tiempo-urgente' : 'tiempo-normal';
                    } elseif ($orden->estadoOrden->nombre === 'Preparada') {
                        $claseOrden = 'orden-preparada';
                        $claseTiempo = 'tiempo-normal';
                    }
                @endphp
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 orden-card {{ $claseOrden }}"
                         data-orden-id="{{ $orden->id }}"
                         data-estado-id="{{ $orden->estadoOrden->id }}"
                         data-estado-nombre="{{ $orden->estadoOrden->nombre }}"
                         data-fecha="{{ $orden->created_at->toISOString() }}"
                         data-tiempo="{{ $orden->tiempo_transcurrido['minutos'] ?? 0 }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Orden #{{ $orden->nro_orden }}</h5>
                                    <p class="text-muted small mb-0 {{ $claseTiempo }}">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $orden->tiempo_transcurrido['humano'] }}
                                        @if($orden->tiempo_transcurrido['es_hoy'])
                                            ({{ $orden->tiempo_transcurrido['minutos'] }} min)
                                        @endif
                                    </p>
                                </div>
                                <x-estado-orden-badge :estado="$orden->estadoOrden"/>
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
                                    <h6 class="mb-0">Total: S/. {{ number_format($orden->itemsOrdenes->sum('monto'), 2) }}</h6>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm ver-detalles"
                                                data-orden-id="{{ $orden->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detalleOrdenModal">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </button>
                                        @if($orden->estadoOrden->nombre === 'Pendiente')
                                            <button class="btn btn-warning btn-sm" onclick="cambiarEstadoRapido({{ $orden->id }}, 'En Proceso')">
                                                <i class="bi bi-play me-1"></i>Iniciar
                                            </button>
                                        @elseif($orden->estadoOrden->nombre === 'En Proceso')
                                            <button class="btn btn-info btn-sm" onclick="cambiarEstadoRapido({{ $orden->id }}, 'Preparada')">
                                                <i class="bi bi-check me-1"></i>Lista
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
                                <div id="msg-cambiar-estado-pagada" class="alert alert-warning mt-2 d-none"
                                     role="alert">
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
@endsection

@push('scripts')
    <script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
    <script src="{{ asset('js/cocinero/orden.js') }}"></script>
@endpush
