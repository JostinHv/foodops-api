@extends('layouts.app')

@section('title', 'Tenant')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/super-admin/tenant.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        @include('super-admin.tenants.partials.header', [
            'title' => 'Gestión de Tenants'
        ])

        @include('super-admin.tenants.partials.tenant-list', [
            'tenants' => $tenants ?? []
        ])
    </div>

    @include('super-admin.tenants.modals.create')
    @include('super-admin.tenants.modals.show')
{{--    @include('super-admin.tenants.modals.delete')--}}
{{--    @include('super-admin.tenants.modals.deactivate')--}}
@endsection

@push('scripts')
    <script src="{{ asset('js/super-admin/tenant-form.js') }}"></script>
@endpush

