<!-- Modal: Nuevo Tenant -->
<div class="modal fade" id="nuevoTenantModal" tabindex="-1" aria-labelledby="nuevoTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formNuevoTenant" action="{{ route('superadmin.tenant.store') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoTenantModalLabel">
                        <i class="bi bi-building me-2"></i>Crear Nuevo Tenant
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Barra de Progreso -->
                <div class="modal-progress">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="steps-container">
                        <div class="step active" data-step="1">
                            <i class="bi bi-building"></i>
                            <span>Información Básica</span>
                        </div>
                        <div class="step" data-step="2">
                            <i class="bi bi-credit-card"></i>
                            <span>Plan de Suscripción</span>
                        </div>
                        <div class="step" data-step="3">
                            <i class="bi bi-person-lines-fill"></i>
                            <span>Contacto</span>
                        </div>
                        <div class="step" data-step="4">
                            <i class="bi bi-check-circle"></i>
                            <span>Confirmación</span>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <!-- Paso 1: Información Básica -->
                    <div class="step-content" id="step1">
                        <div class="step-header mb-4">
                            <h6 class="mb-0"><i class="bi bi-building me-2"></i>Información Básica</h6>
                            <small class="text-muted">Ingrese los datos principales del tenant</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="logo" class="form-label">Logo</label>
                                <div class="image-upload-container">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                                        <input type="file"
                                               class="form-control @error('logo') is-invalid @enderror"
                                               id="logo"
                                               name="logo"
                                               accept="image/jpeg,image/png,image/gif">
                                    </div>
                                    <div class="image-upload-info">
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i>
                                            Formatos permitidos: JPEG, PNG, GIF. Tamaño máximo: 2MB. Dimensiones
                                            máximas: 1200x1200px
                                        </small>
                                    </div>
                                    @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="logo-preview" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="dominio" class="form-label">Dominio <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                    <input type="text"
                                           class="form-control @error('dominio') is-invalid @enderror"
                                           id="dominio"
                                           name="dominio"
                                           value="{{ old('dominio') }}"
                                           placeholder="mirestaurante.com"
                                           required>
                                </div>
                                <div id="dominio-feedback" class="form-text"></div>
                                @error('dominio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('activo') is-invalid @enderror"
                                           type="checkbox"
                                           id="activo"
                                           name="activo"
                                           value="1"
                                           checked>
                                    <label class="form-check-label" for="activo">
                                        <span id="estado-texto">Tenant Activo</span>
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Un tenant inactivo no podrá acceder al sistema ni a ninguno de sus servicios.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Plan de Suscripción -->
                    <div class="step-content d-none" id="step2">
                        <div class="step-header mb-4">
                            <h6 class="mb-0"><i class="bi bi-credit-card me-2"></i>Plan de Suscripción</h6>
                            <small class="text-muted">Seleccione el plan y configure la suscripción</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="plan_suscripcion_id" class="form-label">Seleccione un Plan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('plan_suscripcion_id') is-invalid @enderror"
                                        id="plan_suscripcion_id"
                                        name="plan_suscripcion_id"
                                        required>
                                    <option value="">Seleccione un plan...</option>
                                    @foreach($planes as $plan)
                                        <option value="{{ $plan->id }}"
                                                data-precio="{{ $plan->precio }}"
                                                data-caracteristicas="{{ json_encode($plan->caracteristicas) }}"
                                                data-intervalo="{{ $plan->intervalo }}"
                                                data-descripcion="{{ $plan->descripcion }}"
                                            {{ old('plan_suscripcion_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->nombre }} - S/. {{ number_format($plan->precio, 2) }}
                                            /{{ $plan->intervalo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_suscripcion_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="metodo_pago_id" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                <select class="form-select @error('metodo_pago_id') is-invalid @enderror"
                                        id="metodo_pago_id"
                                        name="metodo_pago_id"
                                        required>
                                    <option value="">Seleccione un método de pago...</option>
                                    @foreach($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}"
                                            {{ old('metodo_pago_id') == $metodo->id ? 'selected' : '' }}>
                                            {{ $metodo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('metodo_pago_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview del Plan Seleccionado -->
                        <div id="plan-preview" class="mt-4 d-none">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Detalles del Plan Seleccionado</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <p class="text-muted" id="descripcion-plan"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>Precio:</span>
                                                <span class="h5 mb-0">S/. <span id="precio-plan">0.00</span></span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span>Intervalo:</span>
                                                <span class="badge rounded-pill bg-primary" id="intervalo-plan">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="limites mb-3">
                                                <div class="limite-item">
                                                    <i class="bi bi-people"></i>
                                                    <div>
                                                        <small>Usuarios</small>
                                                        <strong id="limite-usuarios">0</strong>
                                                    </div>
                                                </div>
                                                <div class="limite-item">
                                                    <i class="bi bi-building"></i>
                                                    <div>
                                                        <small>Restaurantes</small>
                                                        <strong id="limite-restaurantes">0</strong>
                                                    </div>
                                                </div>
                                                <div class="limite-item">
                                                    <i class="bi bi-shop"></i>
                                                    <div>
                                                        <small>Sucursales</small>
                                                        <strong id="limite-sucursales">0</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <small class="d-block mb-2">Características adicionales:</small>
                                            <ul class="list-unstyled mb-0" id="caracteristicas-plan">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                       class="form-control @error('fecha_inicio') is-invalid @enderror"
                                       id="fecha_inicio"
                                       name="fecha_inicio"
                                       value="{{ old('fecha_inicio', date('Y-m-d')) }}"
                                       required>
                                @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="renovacion_automatica" class="form-label">Renovación Automática</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="renovacion_automatica"
                                           name="renovacion_automatica"
                                           value="1"
                                        {{ old('renovacion_automatica') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="renovacion_automatica">
                                        Activar renovación automática
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>
                                    La suscripción se renovará automáticamente al finalizar el período.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Datos de Contacto -->
                    <div class="step-content d-none" id="step3">
                        <div class="step-header mb-4">
                            <h6 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Datos de Contacto</h6>
                            <small class="text-muted">Ingrese la información de contacto del tenant</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="datos_contacto_nombre_empresa" class="form-label">Nombre de la Empresa <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text"
                                           class="form-control @error('datos_contacto.nombre_empresa') is-invalid @enderror"
                                           id="datos_contacto_nombre_empresa"
                                           name="datos_contacto[nombre_empresa]"
                                           value="{{ old('datos_contacto.nombre_empresa') }}"
                                           placeholder="Ej: Restaurantes del Norte"
                                           required>
                                </div>
                                @error('datos_contacto.nombre_empresa')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="datos_contacto_email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email"
                                           class="form-control @error('datos_contacto.email') is-invalid @enderror"
                                           id="datos_contacto_email"
                                           name="datos_contacto[email]"
                                           value="{{ old('datos_contacto.email') }}"
                                           placeholder="admin@empresa.com"
                                           required>
                                </div>
                                @error('datos_contacto.email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="datos_contacto_telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel"
                                           class="form-control @error('datos_contacto.telefono') is-invalid @enderror"
                                           id="datos_contacto_telefono"
                                           name="datos_contacto[telefono]"
                                           value="{{ old('datos_contacto.telefono') }}"
                                           placeholder="+51 999 888 777">
                                </div>
                                @error('datos_contacto.telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="datos_contacto_direccion" class="form-label">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text"
                                           class="form-control @error('datos_contacto.direccion') is-invalid @enderror"
                                           id="datos_contacto_direccion"
                                           name="datos_contacto[direccion]"
                                           value="{{ old('datos_contacto.direccion') }}"
                                           placeholder="Av. Principal 123, Ciudad">
                                </div>
                                @error('datos_contacto.direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Paso 4: Resumen y Confirmación -->
                    <div class="step-content d-none" id="step4">
                        <div class="step-header mb-4">
                            <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Resumen y Confirmación</h6>
                            <small class="text-muted">Revise la información antes de crear el tenant</small>
                        </div>
                        <div class="summary-container">
                            <!-- El contenido se llenará dinámicamente con JavaScript -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-outline-primary" id="prevStep" style="display: none;">
                        <i class="bi bi-arrow-left me-1"></i>Anterior
                    </button>
                    <button type="button" class="btn btn-primary" id="nextStep">
                        Siguiente<i class="bi bi-arrow-right ms-1"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitForm" style="display: none;">
                        <i class="bi bi-check-circle me-1"></i>Crear Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')

@endpush

