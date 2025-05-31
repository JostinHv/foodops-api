<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Último Acceso</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($usuario->foto_perfil)
                                    <img src="{{ Storage::url($usuario->foto_perfil->url) }}"
                                        alt="Foto {{ $usuario->nombres }}"
                                        class="rounded-circle"
                                        width="40" height="40">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $usuario->nombres }} {{ $usuario->apellidos }}</h6>
                                <small class="text-muted">{{ $usuario->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('superadmin.tenant.usuarios.cambiar-rol', ['tenantId' => $tenant->id, 'usuarioId' => $usuario->id]) }}"
                            method="POST"
                            class="cambiar-rol-form">
                            @csrf
                            @method('PUT')
                            <select class="form-select form-select-sm"
                                name="rol_id"
                                style="width: 140px;"
                                {{ $usuario->roles->contains('nombre', 'administrador') ? 'disabled' : '' }}>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}"
                                        {{ $usuario->roles->contains('id', $rol->id) ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-estado"
                                type="checkbox"
                                {{ $usuario->activo ? 'checked' : '' }}
                                {{ $usuario->roles->contains('nombre', 'administrador') ? 'disabled' : '' }}
                                data-usuario-id="{{ $usuario->id }}"
                                data-tenant-id="{{ $tenant->id }}">
                            <span class="badge {{ $usuario->activo ? 'bg-success' : 'bg-danger' }}">
                                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </td>
                    <td>
                        @if($usuario->ultimo_acceso)
                            <span title="{{ $usuario->ultimo_acceso->format('d/m/Y H:i:s') }}">
                                {{ $usuario->ultimo_acceso->diffForHumans() }}
                            </span>
                        @else
                            <span class="text-muted">Nunca</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if(!$usuario->roles->contains('nombre', 'administrador'))
                            <form action="{{ route('superadmin.tenant.usuarios.destroy', ['tenantId' => $tenant->id, 'usuarioId' => $usuario->id]) }}"
                                method="POST"
                                class="d-inline eliminar-usuario-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar usuario">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            <p class="mb-0">No hay usuarios registrados</p>
                            <small>Utiliza el botón "Agregar Usuario" para comenzar</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

