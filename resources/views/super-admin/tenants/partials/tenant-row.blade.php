<tr>
    <td>
        <div class="d-flex align-items-center">
            <!-- Logo -->
            <div class="tenant-logo me-3">
                @if($tenant->logo)
                    <img src="{{ Storage::url($tenant->logo->url) }}"
                         alt="Logo {{ $tenant->datos_contacto['nombre_empresa'] ?? 'Tenant' }}"
                         class="img-fluid rounded">
                @else
                    <div class="no-logo">
                        <i class="bi bi-building text-muted"></i>
                    </div>
                @endif
            </div>
            <!-- Información -->
            <div>
                <strong>
                    {{$tenant->dominio}}
                </strong><br>
                <small class="text-muted">
                    {{ $tenant->datos_contacto['nombre_empresa'] }}
                </small>
            </div>
        </div>
    </td>
    <td>
        @if($tenant->suscripcion && $tenant->suscripcion->planSuscripcion)
            {{ $tenant->suscripcion->planSuscripcion->nombre }}
        @else
            <span class="text-muted">Sin Plan</span>
        @endif
    </td>
    <td class=" d-none d-sm-table-cell">{{ $tenant->restaurantes_count ?? 0 }}</td>
    <td class="d-none d-sm-table-cell">{{ $tenant->usuarios_count ?? 0 }}</td>
    <td>
        <form action="{{ route('superadmin.tenant.toggle-activo', $tenant->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit"
                    class="btn btn-sm {{ $tenant->activo ? 'btn-success' : 'btn-warning' }}"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="{{ $tenant->activo ? 'Desactivar' : 'Activar' }}">
                <i class="bi {{ $tenant->activo ? 'bi-toggle-on' : 'bi-toggle-off' }} fs-5"></i>
            </button>
        </form>
    </td>
    <td class="d-none d-md-table-cell">{{ $tenant->updated_at?->diffForHumans() ?? ''}}</td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('superadmin.tenant.show', $tenant->id) }}"
               class="btn btn-sm btn-outline-secondary"
               data-bs-toggle="tooltip"
               data-bs-placement="top"
               title="Ver detalles">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </td>
</tr>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar todos los tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .tenant-logo {
            width: 40px;
            height: 40px;
            min-width: 40px; /* Evita que el logo se encoja */
            overflow: hidden;
            border-radius: 4px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tenant-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Mantiene la proporción y cubre el espacio */
            object-position: center;
        }

        .tenant-logo .no-logo {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .tenant-logo .no-logo i {
            font-size: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .tenant-logo {
                width: 32px;
                height: 32px;
                min-width: 32px;
            }

            .tenant-logo .no-logo i {
                font-size: 1rem;
            }
        }
    </style>
@endpush

