<div class="card h-100">
    <div class="card-header">
        <h5 class="card-title mb-0">Información del Tenant</h5>
    </div>
    <div class="card-body">
        <div class="text-center mb-3">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo->url) }}"
                    alt="Logo {{ $tenant->datos_contacto['nombre_empresa'] }}"
                    class="img-thumbnail"
                    style="max-height: 100px">
            @else
                <div class="border rounded p-3 text-muted">
                    <i class="bi bi-building fs-1"></i>
                    <p class="small mb-0">Sin logo</p>
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label text-muted">Dominio</label>
            <p class="mb-0">{{ $tenant->dominio }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label text-muted">Estado</label>
            <p class="mb-0">
                <span class="badge {{ $tenant->activo ? 'bg-success' : 'bg-danger' }}">
                    {{ $tenant->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </p>
        </div>

        @if(isset($tenant->datos_contacto['email']))
            <div class="mb-3">
                <label class="form-label text-muted">Email</label>
                <p class="mb-0">{{ $tenant->datos_contacto['email'] }}</p>
            </div>
        @endif

        @if(isset($tenant->datos_contacto['telefono']))
            <div class="mb-0">
                <label class="form-label text-muted">Teléfono</label>
                <p class="mb-0">{{ $tenant->datos_contacto['telefono'] }}</p>
            </div>
        @endif
    </div>
</div>
