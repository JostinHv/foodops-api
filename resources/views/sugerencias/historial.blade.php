@extends('layouts.app')

@section('title', 'Mi Historial de Sugerencias')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sugerencias.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card sugerencia-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Mi historial de sugerencias</h4>
                    <a href="{{ route('sugerencias.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Nueva sugerencia
                    </a>
                </div>
                <div class="card-body">
                    @if($sugerencias->isEmpty())
                        <div class="alert alert-info">Aún no has enviado sugerencias.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Sugerencia</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sugerencias as $sugerencia)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ Str::limit($sugerencia->sugerencia, 80) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $sugerencia->estado === 'pendiente' ? 'warning text-dark' : ($sugerencia->estado === 'revisada' ? 'info text-dark' : 'success') }}">{{ ucfirst($sugerencia->estado) }}</span>
                                            </td>
                                            <td>{{ $sugerencia->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 