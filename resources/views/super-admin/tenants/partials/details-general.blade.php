<div class="row mb-4">
    <!-- Logo -->
    <div class="col-12 text-center mb-3">
        @if($tenant->logo)
            <img src="{{ Storage::url($tenant->logo->url) }}" alt="Logo {{ $tenant->datos_contacto['nombre_empresa'] }}"
                class="img-thumbnail" style="max-height: 100px">
        @else
            <div class="border rounded p-3 text-muted">
                <i class="bi bi-building fs-1"></i>
                <p class="small mb-0">Sin logo</p>
            </div>
        @endif
    </div>

    <!-- Información Básica -->
    <div class="col-12">
        <h6 class="border-bottom pb-2 mb-3">Información Básica</h6>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Dominio</div>
            <div class="col-sm-9">
                <a href="https://{{ $tenant->dominio }}" target="_blank" class="text-decoration-none">
                    {{ $tenant->dominio }} <i class="bi bi-box-arrow-up-right small"></i>
                </a>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Estado</div>
            <div class="col-sm-9">
                @if($tenant->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Datos de Contacto -->
    <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 mb-3">Datos de Contacto</h6>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Empresa</div>
            <div class="col-sm-9">{{ $tenant->datos_contacto['nombre_empresa'] }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Email</div>
            <div class="col-sm-9">
                <a href="mailto:{{ $tenant->datos_contacto['email'] }}" class="text-decoration-none">
                    {{ $tenant->datos_contacto['email'] }}
                </a>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Teléfono</div>
            <div class="col-sm-9">
                <a href="tel:{{ $tenant->datos_contacto['telefono'] }}" class="text-decoration-none">
                    {{ $tenant->datos_contacto['telefono'] }}
                </a>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Dirección</div>
            <div class="col-sm-9">{{ $tenant->datos_contacto['direccion'] }}</div>
        </div>
    </div>

    <!-- Fechas -->
    <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 mb-3">Información del Sistema</h6>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Creado</div>
            <div class="col-sm-9">{{ $tenant->created_at->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-3 fw-bold">Actualizado</div>
            <div class="col-sm-9">{{ $tenant->updated_at->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>
</div>
