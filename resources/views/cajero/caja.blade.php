@extends('layouts.app')

@section('title', 'Caja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cajero/caja.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Caja </h2>
                <p class="text-muted mb-0">Visualiza tu apertura y cierre de caja</p>
            </div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facturarModal">
                <i class="bi bi-plus-circle me-2"></i>Apertura de Caja
            </a>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <p> Buscar por Fechas:</p> 
                    </div>
                    <!-- Filtro por rango de fechas -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-calendar"></i>
                            </span>
                            <input type="date" name="fecha_inicio" class="form-control" 
                                value="">
                            <span class="input-group-text bg-white">a</span>
                            <input type="date" name="fecha_fin" class="form-control" 
                                value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Caja -->
        
    </div>
@endsection