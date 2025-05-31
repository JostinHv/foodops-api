<div class="card h-100">
    <div class="card-header">
        <h5 class="card-title mb-0">Estadísticas de Usuarios</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-4">
                <div class="border rounded p-3 text-center">
                    <h3 class="mb-1">{{ $usuarios->count() }}</h3>
                    <p class="text-muted mb-0">Total Usuarios</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="border rounded p-3 text-center">
                    <h3 class="mb-1">{{ $usuarios->where('activo', true)->count() }}</h3>
                    <p class="text-muted mb-0">Usuarios Activos</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="border rounded p-3 text-center">
                    <h3 class="mb-1">{{ $usuarios->where('activo', false)->count() }}</h3>
                    <p class="text-muted mb-0">Usuarios Inactivos</p>
                </div>
            </div>

            <div class="col-12">
                <div class="border rounded p-3">
                    <h6 class="mb-3">Distribución por Roles</h6>
                    @foreach($roles as $rol)
                        @php
                            $count = $usuarios->filter(function($user) use ($rol) {
                                return $user->roles->contains('id', $rol->id);
                            })->count();
                            $percentage = $usuarios->count() > 0 ? ($count / $usuarios->count()) * 100 : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>{{ $rol->nombre }}</span>
                                <span class="badge bg-primary">{{ $count }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $percentage }}%;"
                                    aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
