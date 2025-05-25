@extends('layouts.app')

@section('title', 'Nueva Orden')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mesero/nuevaorden.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Nueva Orden</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orden-store-submit') }}" method="POST">
                @csrf

                <h6>Información de la Orden</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="cliente" class="form-label">Nombre Completo del Cliente</label>
                        <input type="text" name="cliente" id="cliente" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mesa_id" class="form-label">Mesa</label>
                        <select name="mesa_id" id="mesa_id" class="form-select">
                            <option value="">Seleccione</option>
                            @foreach ($mesas as $mesa)
                                <option value="{{ $mesa->id }}">{{ $mesa->nombre }} - {{$mesa->capacidad}} Personas
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h6>Detalle de la Orden</h6>
                <div class="row mb-3 align-items-end">
                    <div class="col-md-6">
                        <label for="producto_id" class="form-label">Platos y Bebidas</label>
                        <select id="producto_id" class="form-select"> <!-- Eliminar name="producto_id" -->
                            <option value="">Seleccione</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre }} -
                                    S/. {{ $producto->precio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-dark w-100" id="agregar-producto">Agregar</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="detalle-tabla">
                        <thead class="table-secondary">
                        <tr>
                            <th>Plato/Bebida</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- JS insertará filas aquí --}}
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Añadir Orden</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/mesero/nuevaorden.js') }}"></script>
@endpush
