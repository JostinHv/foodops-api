<tr>
    <td>
        <div class="d-flex align-items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 me-3" style="width: 40px; height: 40px;">
                @if($tenant->logo)
                    <img src="{{ Storage::url($tenant->logo->url) }}"
                         alt="Logo {{ $tenant->datos_contacto['nombre_empresa'] ?? 'Tenant' }}"
                         class="img-fluid rounded">
                @else
                    <div class="border rounded d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-building text-muted"></i>
                    </div>
                @endif
            </div>
            <!-- Información -->
            <div>
                <strong>
                    @if(isset($tenant->datos_contacto['nombre_empresa']))
                        {{ $tenant->datos_contacto['nombre_empresa'] }}
                    @else
                        {{ $tenant->dominio }}
                    @endif
                </strong><br>
                <small class="text-muted">
                    @if(isset($tenant->datos_contacto['email']))
                        {{ $tenant->datos_contacto['email'] }}
                    @else
                        {{ $tenant->dominio }}
                    @endif
                </small>
            </div>
        </div>
    </td>
    <td>
        @include('super-admin.tenants.partials.plan-badge', ['plan' => $tenant->plan])
    </td>
    <td>{{ $tenant->restaurantes_count ?? 0 }}</td>
    <td>{{ $tenant->usuarios_count ?? 0 }}</td>
    <td>
        <form action="{{ route('superadmin.tenant.toggle-activo', $tenant->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm {{ $tenant->activo ? 'btn-success' : 'btn-warning' }}">
                {{ $tenant->activo ? 'Activo' : 'Inactivo' }}
            </button>
        </form>
    </td>
    <td>{{ $tenant->updated_at?->diffForHumans() ?? ''}}</td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('superadmin.tenant.show', $tenant->id) }}"
               class="btn btn-sm btn-outline-secondary"
               title="Ver detalles">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </td>
</tr>

