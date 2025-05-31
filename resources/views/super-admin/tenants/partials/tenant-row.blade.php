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
        @if($tenant->activo)
            <span class="badge bg-success">Activo</span>
        @else
            <span class="badge bg-danger">Inactivo</span>
        @endif
    </td>
    <td>{{ $tenant->updated_at?->diffForHumans() ?? ''}}</td>
    <td>
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-outline-primary"
                    title="Ver detalles"
                    data-bs-toggle="modal"
                    data-bs-target="#detallesTenantModal"
                    data-tenant="{{ json_encode($tenant) }}">
                <i class="bi bi-eye"></i>
            </button>
            <a href="{{ route('superadmin.tenant.update', $tenant->id) }}"
               class="btn btn-sm btn-outline-secondary"
               title="Editar">
                <i class="bi bi-pencil"></i>
            </a>
            @if($tenant->activo)
                <form action="{{ route('superadmin.tenant.toggle-activo', $tenant->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Está seguro de que desea desactivar este tenant?');">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="btn btn-sm btn-outline-danger"
                            title="Desactivar">
                        <i class="bi bi-person-dash"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('superadmin.tenant.toggle-activo', $tenant->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Está seguro de que desea activar este tenant?');">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="btn btn-sm btn-outline-success"
                            title="Activar">
                        <i class="bi bi-person-check"></i>
                    </button>
                </form>
            @endif
            <form action="{{ route('superadmin.tenant.destroy', $tenant->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('¿Está seguro de que desea eliminar este tenant?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-sm btn-outline-danger"
                        title="Eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>

