@extends('layouts.app')

@section('title', 'Órdenes del Mesero')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Órdenes</h2>
        <a href="{{route('orden-nueva')}}" class="btn btn-dark">+ Nueva Orden</a>
    </div>

    <div class="row g-4">
        @for ($i = 1; $i <= 6; $i++)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Orden #00{{ $i }}</h5>
                        <span class="badge bg-danger mb-2">Estado</span>
                        <p class="card-text">Texto descriptivo de la orden.</p>
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-secondary btn-sm">Ver Detalles</a>
                            <a href="#" class="btn btn-outline-dark btn-sm">Marcar Servida</a>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@endsection
