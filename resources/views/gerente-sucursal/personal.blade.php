@extends('layouts.app')

@section('title', 'Personal')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/gerente-sucursal/personal.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestión de Personal</h1>
            <p class="mb-0 text-muted">Administra empleados y horarios</p>
        </div>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#nuevoEmpleadoModal">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Empleado
        </a>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Personal Activo</div>
                    <h3 class="mb-1">14</h3>
                    <small class="text-success">+2 desde ayer</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">En Servicio</div>
                    <h3 class="mb-1">12</h3>
                    <small class="text-muted">86% del personal</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Horas Trabajadas</div>
                    <h3 class="mb-1">96h</h3>
                    <small class="text-success">Hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fw-bold">Ventas/Mesero</div>
                    <h3 class="mb-1">S/ 350</h3>
                    <small class="text-muted">Promedio hoy</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de empleados -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Personal Activo</h5>
            <div>
                <span class="badge bg-primary me-2">15 empleados</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Contacto</th>
                            <th>Turno</th>
                            <th>Salario</th>
                            <th>Estado</th>
                            <th>Antigüedad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ejemplo de empleado 1 -->
                        <tr>
                            <td>
                                <strong>Carlos Mendoza</strong>
                                <div class="text-muted small">DNI: 12345678</div>
                            </td>
                            <td>
                                <span class="badge bg-info">Mesero</span>
                            </td>
                            <td>
                                <div>carlos@restaurante.com</div>
                                <div class="text-muted small">999-123-456</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">Mañana</span>
                            </td>
                            <td>
                                S/ 1,200.00
                            </td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>
                                <div class="text-muted small">1 año 3 meses</div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles" data-bs-toggle="modal" data-bs-target="#verEmpleadoModal">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar" data-bs-toggle="modal" data-bs-target="#editarEmpleadoModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Desactivar">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Ejemplo de empleado 2 -->
                        <tr>
                            <td>
                                <strong>Ana López</strong>
                                <div class="text-muted small">DNI: 87654321</div>
                            </td>
                            <td>
                                <span class="badge bg-info">Mesera</span>
                            </td>
                            <td>
                                <div>ana@restaurante.com</div>
                                <div class="text-muted small">987-654-321</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">Tarde</span>
                            </td>
                            <td>
                                S/ 1,250.00
                            </td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>
                                <div class="text-muted small">2 años 1 mes</div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Desactivar">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Ejemplo de empleado 3 -->
                        <tr>
                            <td>
                                <strong>Pedro García</strong>
                                <div class="text-muted small">DNI: 45678912</div>
                            </td>
                            <td>
                                <span class="badge bg-danger text-white">Cocinero</span>
                            </td>
                            <td>
                                <div>pedro@restaurante.com</div>
                                <div class="text-muted small">912-345-678</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">Completo</span>
                            </td>
                            <td>
                                S/ 2,800.00
                            </td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td>
                                <div class="text-muted small">3 años 6 meses</div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Desactivar">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Ejemplo de empleado 4 -->
                        <tr>
                            <td>
                                <strong>María Santos</strong>
                                <div class="text-muted small">DNI: 78912345</div>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">Cajera</span>
                            </td>
                            <td>
                                <div>maria@restaurante.com</div>
                                <div class="text-muted small">945-678-123</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">Mañana</span>
                            </td>
                            <td>
                                S/ 1,500.00
                            </td>
                            <td>
                                <span class="badge bg-secondary">Inactivo</span>
                            </td>
                            <td>
                                <div class="text-muted small">1 año 8 meses</div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" title="Activar">
                                        <i class="bi bi-person-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>


<!-- Modal: Nuevo Empleado -->
<div class="modal fade" id="nuevoEmpleadoModal" tabindex="-1" aria-labelledby="nuevoEmpleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="nuevoEmpleadoForm" action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoEmpleadoModalLabel">Nuevo Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Registra un nuevo miembro del personal</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="juan@ejemplo.com" required>
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="999-123-456">
                        </div>
                        <div class="col-md-6">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" placeholder="12345678" required>
                        </div>

                        <div class="col-md-6">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="" selected disabled>Seleccionar rol</option>
                                <option value="gerente">Gerente</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="mesero">Mesero</option>
                                <option value="cocinero">Cocinero</option>
                                <option value="ayudante">Ayudante</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="turno" class="form-label">Turno</label>
                            <select class="form-select" id="turno" name="turno" required>
                                <option value="" selected disabled>Seleccionar turno</option>
                                <option value="mañana">Mañana</option>
                                <option value="tarde">Tarde</option>
                                <option value="noche">Noche</option>
                                <option value="completo">Completo</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="salario_base" class="form-label">Salario Base</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" step="0.01" class="form-control" id="salario_base" name="salario_base" placeholder="1200.00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Empleado</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection