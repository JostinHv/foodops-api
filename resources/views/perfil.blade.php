@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endpush

@section('content')
    <div class="container py-4">
        <div class="row">
            <!-- Información del Perfil -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Información Personal</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($usuario->foto_perfil_id)
                            <img src="{{ asset('storage/' . $usuario->fotoPerfil->ruta) }}"
                                 alt="Foto de perfil"
                                 class="rounded-circle mb-3"
                                 width="120" height="120">
                        @else
                            <div
                                class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3 mx-auto"
                                style="width: 120px; height: 120px;">
                                <i class="bi bi-person-circle" style="font-size: 3rem; color: #6c757d;"></i>
                            </div>
                        @endif

                        <h4 class="mb-1">{{ $usuario->nombres }} {{ $usuario->apellidos }}</h4>
                        @foreach($usuario->roles as $role)
                            <span class="badge bg-primary">{{ ($role->nombre) }}</span>
                        @endforeach

                        <ul class="list-unstyled text-start">
                            <li class="mb-2">
                                <i class="bi bi-envelope me-2"></i>
                                {{ $usuario->email }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-telephone me-2"></i>
                                {{ $usuario->celular ?? 'No especificado' }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-calendar me-2"></i>
                                Fecha de ingreso: {{ $usuario->created_at->format('d/m/Y') ?? 'No especificada' }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock-history me-2"></i>
                                Último
                                acceso: {{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->format('d/m/Y') : 'Nunca' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Información de Trabajo -->
            <div class="col-md-8">
                @if($sucursal && $restaurante)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Información de Trabajo</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Restaurante</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-shop me-2"></i>
                                            {{ $restaurante->nombre_legal ?? 'No especificado' }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            {{ $restaurante->direccion ?? 'No especificada' }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-telephone me-2"></i>
                                            {{ $restaurante->telefono ?? 'No especificado' }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-envelope me-2"></i>
                                            {{ $restaurante->email ?? 'No especificado' }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Sucursal</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-building me-2"></i>
                                            {{ $sucursal->nombre ?? 'No especificado' }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            {{ $sucursal->direccion ?? 'No especificada' }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-telephone me-2"></i>
                                            {{ $sucursal->telefono ?? 'No especificado' }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-clock me-2"></i>
                                            {{ $sucursal->hora_apertura?->format('H:i') ?? 'Horario no especificado' }}
                                            - {{ $sucursal->hora_cierre?->format('H:i') ?? 'Horario no especificado' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


