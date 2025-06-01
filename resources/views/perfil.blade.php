@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Tarjeta de Información Personal -->
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ Auth::user()->foto ?? asset('images/default-user.png') }}" 
                         alt="Foto de perfil" 
                         class="rounded-circle mb-3" 
                         width="120" height="120">
                    
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3">{{ ucfirst(Auth::user()->role) }}</p>
                    
                    <ul class="list-unstyled text-start">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            {{ Auth::user()->email }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            {{ Auth::user()->telefono ?? '+51 XXX XXX XXX' }}
                        </li>
                        <li>
                            <i class="bi bi-calendar me-2"></i>
                            Fecha de ingreso: {{ Auth::user()->created_at->format('d/m/Y') }}
                        </li>
                        <li>
                            <i class="bi bi-clock-history me-2"></i>
                            Último acceso: {{ now()->format('d/m/Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Tarjeta de Lugar de Trabajo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Lugar de Trabajo</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">Delicias Centro - Miraflores</h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Restaurante</h6>
                            <p class="mb-1">Delicias del Centro</p>
                            <p class="text-muted">
                                <i class="bi bi-geo-alt"></i> Av. Larco 123, Miraflores, Lima
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6 text-center">
                                    <h3 class="mb-0">80</h3>
                                    <small class="text-muted">Capacidad</small>
                                </div>
                                <div class="col-6 text-center">
                                    <h3 class="mb-0">15</h3>
                                    <small class="text-muted">Personal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Permisos:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success">Manage Branch</span>
                        <span class="badge bg-success">Manage Staff</span>
                        <span class="badge bg-success">View Branch Reports</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>