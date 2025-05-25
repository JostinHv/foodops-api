@extends('layouts.app')

@section('title', 'Órdenes del Mesero')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Órdenes</h2>
        <a href="{{ route('orden-store') }}" class="btn btn-dark">+ Nueva Orden</a>
    </div>

    <div class="row g-4">
        @forelse ($ordenes as $orden)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Orden #{{ $orden->nro_orden }}</h5>
                        <span class="badge bg-{{ $orden->estadoOrden->nombre === 'Pendiente' ? 'danger' :
                                              ($orden->estadoOrden->nombre === 'En Proceso' ? 'warning' : 'success') }} mb-2">
                            {{ $orden->estadoOrden->nombre }}
                        </span>
                        <p class="card-text">
                            Mesa: {{ $orden->mesa->nombre }}<br>
                            Cliente: {{ $orden->nombre_cliente ?: 'Sin especificar' }}
                        </p>
                        <div class="d-flex justify-content-between">
{{--                            <a href="{{ route('ordenes', $orden->id) }}" class="btn btn-outline-secondary btn-sm">Ver--}}
{{--                                Detalles</a>--}}
                            @if($orden->estadoOrden->nombre !== 'Servida')
{{--                                <form action="{{ route('ordenes.marcar-servida', $orden->id) }}" method="POST"--}}
{{--                                      class="d-inline">--}}
{{--                                    @csrf--}}
{{--                                    <button type="submit" class="btn btn-outline-dark btn-sm">Marcar Servida</button>--}}
{{--                                </form>--}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No hay órdenes disponibles en este momento.
                </div>
            </div>
        @endforelse
    </div>
@endsection
