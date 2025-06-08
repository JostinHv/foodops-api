@extends('layouts.app')

@section('title', 'Tenants - Super Admin')

@push('styles')
        <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/super-admin/tenant.css') }}">
        <link rel="stylesheet" href="{{ asset('css/super-admin/tenant-modal.css') }}">
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Gestión de Tenants</h1>
            </div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoTenantModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Tenant
            </a>
        </div>

        @include('super-admin.tenants.partials.tenant-list', [
            'tenants' => $tenants ?? []
        ])
    </div>

    @include('super-admin.tenants.modals.create')
    @include('super-admin.tenants.modals.show')
@endsection

@push('scripts')
    <script src="{{ asset('js/super-admin/tenant-form.js') }}"></script>
@endpush

