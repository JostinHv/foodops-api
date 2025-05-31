<div class="row mb-2">
    <div class="col-sm-3 fw-bold">Plan Actual</div>
    <div class="col-sm-9">
        <span class="badge {{ $tenant->plan === 'Premium' ? 'bg-success' : ($tenant->plan === 'Estándar' ? 'bg-primary' : 'bg-secondary') }}"
            id="tenant-plan">{{ $tenant->plan }}</span>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-3 fw-bold">Precio Mensual</div>
    <div class="col-sm-9" id="tenant-precio">${{ number_format($tenant->suscripcion->precio, 2) }}</div>
</div>
<div class="row mb-2">
    <div class="col-sm-3 fw-bold">Próxima Facturación</div>
    <div class="col-sm-9" id="tenant-facturacion">{{ $tenant->suscripcion->proxima_facturacion->format('d/m/Y') }}</div>
</div>
<div class="row">
    <div class="col-sm-3 fw-bold">Estado</div>
    <div class="col-sm-9">
        <span class="badge {{ $tenant->estado === 'Active' ? 'bg-success' : ($tenant->estado === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}"
            id="tenant-estado">{{ $tenant->estado }}</span>
    </div>
</div>

