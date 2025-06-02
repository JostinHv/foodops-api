@extends('layouts.app')

@section('title', 'Nueva Orden')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mesero/nuevaorden.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <form id="ordenForm" action="{{ route('mesero.orden.store.submit') }}" method="POST">
            @csrf
            <!-- Header con información básica -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" name="cliente" id="cliente" class="form-control form-control-lg"
                                   placeholder="Nombre del cliente" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Seleccionar Mesa</label>
                            <input type="hidden" name="mesa_id" id="mesa_id" required>
                            <div class="mesas-container">
                                <div class="row g-2">
                                    @foreach ($mesas as $mesa)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            <div class="mesa-card" data-id="{{ $mesa->id }}">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title mb-1">Mesa {{ $mesa->nombre }}</h5>
                                                        <p class="card-text mb-0">
                                                            <i class="bi bi-people"></i> {{ $mesa->capacidad }} personas
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Panel izquierdo: Productos -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Productos</h5>
                                <div class="input-group" style="max-width: 300px;">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="buscarProducto" class="form-control"
                                           placeholder="Buscar producto...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="productos-container">
                                @foreach ($productosPorCategoria as $grupo)
                                    <div class="categoria-productos mb-4">
                                        <h6 class="categoria-titulo px-3 py-2 bg-light border-bottom">
                                            {{ $grupo['categoria']->nombre }}
                                        </h6>
                                        <div class="row g-0" id="lista-productos">
                                            @foreach ($grupo['productos'] as $producto)
                                                <div class="col-6 col-md-4 col-lg-3 p-2 producto-item"
                                                     data-nombre="{{ strtolower($producto->nombre) }}">
                                                    <div class="card h-100 producto-card"
                                                         data-id="{{ $producto->id }}"
                                                         data-nombre="{{ $producto->nombre }}"
                                                         data-precio="{{ $producto->precio }}">
                                                        <div class="card-body p-2">
                                                            <h6 class="card-title mb-1 text-truncate">{{ $producto->nombre }}</h6>
                                                            <p class="card-text mb-2">
                                                                S/. {{ number_format($producto->precio, 2) }}</p>
                                                            <div
                                                                class="d-flex align-items-center justify-content-center">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-outline-secondary me-2 btn-cantidad-menos">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                                <input type="number"
                                                                       class="form-control form-control-sm text-center cantidad-input"
                                                                       value="0" min="0" style="width: 50px;">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-outline-secondary ms-2 btn-cantidad-mas">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel derecho: Resumen de la orden -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Resumen de la Orden</h5>
                            <span class="badge bg-primary" id="total-productos">0 productos</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="detalle-tabla">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cant.</th>
                                        <th class="text-end">Subtotal</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{-- JS insertará filas aquí --}}
                                    </tbody>
                                    <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2" class="text-end"><strong id="total-orden">S/. 0.00</strong></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Crear Orden
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/mesero/models/Producto.js') }}"></script>
    <script src="{{ asset('js/mesero/models/Orden.js') }}"></script>
    <script src="{{ asset('js/mesero/services/OrdenService.js') }}"></script>
    <script src="{{ asset('js/mesero/nuevaorden.js') }}"></script>
@endpush
