@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    @include('super-admin.usuarios.partials.alerts')
    @include('super-admin.usuarios.partials.header', ['tenant' => $tenant])

    <div class="row mb-4">
        <div class="col-md-4">
            @include('super-admin.usuarios.partials.tenant-info', ['tenant' => $tenant])
        </div>
        <div class="col-md-8">
            @include('super-admin.usuarios.partials.stats', [
                'usuarios' => $usuarios,
                'roles' => $roles
            ])
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Usuarios del Tenant</h5>
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#agregarUsuarioModal">
                <i class="bi bi-person-plus me-2"></i>Agregar Usuario
            </button>
        </div>
        <div class="card-body">
            @include('super-admin.usuarios.partials.users-table', [
                'usuarios' => $usuarios,
                'roles' => $roles,
                'tenant' => $tenant
            ])
        </div>
    </div>
</div>

@include('super-admin.usuarios.modals.create', [
    'roles' => $roles,
    'tenant' => $tenant
])

@push('scripts')
    <script src="{{ asset('js/super-admin/usuarios.js') }}"></script>
@endpush

@endsection
