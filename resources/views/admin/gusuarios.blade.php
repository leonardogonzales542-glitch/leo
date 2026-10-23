@extends('layouts.app')
@section('title', 'Usuarios - AgriStock')

@section('content')
<style>
    /* Estilos personalizados para la vista Usuarios según diseño */
    .usuarios-page-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .btn-nuevo-usuario {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        padding: 0.55rem 1.25rem !important;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.15);
        transition: all 0.2s ease;
    }

    .btn-nuevo-usuario:hover {
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);
    }

    .card-usuarios {
        background-color: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
    }

    .search-input-usuarios {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 0.88rem !important;
        padding: 0.48rem 0.9rem !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .search-input-usuarios::placeholder {
        color: #94a3b8;
    }

    .search-input-usuarios:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        outline: none;
    }

    .table-usuarios {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-usuarios thead th {
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        color: #64748b !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.06em !important;
        text-transform: uppercase !important;
        padding: 0.9rem 1rem !important;
        background-color: transparent !important;
    }

    .table-usuarios tbody tr {
        border-bottom: 1px solid #f1f5f9 !important;
        transition: background-color 0.15s ease;
    }

    .table-usuarios tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table-usuarios tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        padding: 1.05rem 1rem !important;
        vertical-align: middle;
    }

    .usuario-nombre {
        color: #1e293b;
        font-weight: 500;
        font-size: 0.92rem;
    }

    .usuario-correo {
        color: #334155;
        font-size: 0.9rem;
    }

    .usuario-rol {
        color: #334155;
        font-size: 0.9rem;
    }

    .badge-estado-activo {
        display: inline-block;
        background-color: #dcfce7;
        color: #16a34a;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 9999px;
        line-height: 1.4;
    }

    .badge-estado-inactivo {
        display: inline-block;
        background-color: #e2e8f0;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 9999px;
        line-height: 1.4;
    }

    .btn-table-action {
        background: transparent;
        border: none;
        padding: 4px;
        cursor: pointer;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.15s ease, opacity 0.15s ease;
    }

    .btn-table-action:hover {
        transform: scale(1.15);
        opacity: 0.85;
    }

    .action-icon-edit {
        color: #3b82f6;
        font-size: 1.05rem;
    }

    .action-icon-delete {
        color: #ef4444;
        font-size: 1.05rem;
    }

    /* Ocultar barra de búsqueda por defecto de DataTables para usar la nuestra */
    .dataTables_wrapper .dataTables_filter {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_length {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.85rem;
        color: #64748b;
        padding-top: 1rem;
    }

    .dataTables_wrapper .paginate_button.current {
        background: #2563eb !important;
        color: #fff !important;
        border-color: #2563eb !important;
        border-radius: 6px !important;
    }
</style>

<div class="usuarios-page-container py-3">
    
    <!-- Encabezado de página -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a; font-size: 1.85rem; letter-spacing: -0.02em;">
                Usuarios
            </h2>
            <p class="mb-0" style="color: #64748b; font-size: 0.95rem;">
                Administra las cuentas con acceso a la plataforma.
            </p>
        </div>
        <button type="button" class="btn btn-nuevo-usuario d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="fa-solid fa-plus fs-6"></i>
            <span>Nuevo usuario</span>
        </button>
    </div>

    <!-- Tarjeta principal con buscador y tabla -->
    <div class="card card-usuarios p-4">
        
        <!-- Fila de Búsqueda -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <label for="customSearchInput" class="mb-0 fw-medium" style="color: #64748b; font-size: 0.9rem;">Buscar</label>
            <div style="width: 290px; max-width: 100%;">
                <input type="text" id="customSearchInput" class="form-control search-input-usuarios" placeholder="Nombre o correo" autocomplete="off">
            </div>
        </div>

        <!-- Tabla de Usuarios -->
        <div class="table-responsive">
            <table id="usuariosTable" class="table table-usuarios align-middle">
                <thead>
                    <tr>
                        <th class="ps-2">NOMBRE</th>
                        <th>CORREO</th>
                        <th>ROL</th>
                        <th>ESTADO</th>
                        <th class="text-center" style="width: 110px;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        @php
                            $rolNombre = $usuario->rol?->nombre ?? match((string)$usuario->id_rol) {
                                '1' => 'Administrador',
                                '2' => 'Vendedor',
                                '3' => 'Cliente',
                                default => ucfirst((string)$usuario->id_rol)
                            };

                            $estadoLower = strtolower(trim((string)$usuario->estado));
                            $isActivo = in_array($estadoLower, ['activo', '1', 'true', 'activa']);
                        @endphp
                        <tr>
                            <td class="ps-2 usuario-nombre">
                                {{ $usuario->usuario }}
                            </td>
                            <td class="usuario-correo">
                                {{ $usuario->email }}
                            </td>
                            <td class="usuario-rol">
                                {{ $rolNombre }}
                            </td>
                            <td>
                                @if ($isActivo)
                                    <span class="badge-estado-activo">Activo</span>
                                @else
                                    <span class="badge-estado-inactivo">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="button" 
                                        class="btn-table-action btn-editar" 
                                        title="Editar"
                                        data-id="{{ $usuario->id_usuario }}"
                                        data-name="{{ $usuario->usuario }}"
                                        data-email="{{ $usuario->email }}"
                                        data-rol="{{ $usuario->id_rol }}"
                                        data-estado="{{ $usuario->estado }}">
                                        <i class="fa-solid fa-pen action-icon-edit"></i>
                                    </button>
                                    <button type="button" 
                                        class="btn-table-action btn-eliminar" 
                                        title="Eliminar"
                                        data-id="{{ $usuario->id_usuario }}"
                                        data-name="{{ $usuario->usuario }}">
                                        <i class="fa-regular fa-trash-can action-icon-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Modal Crear Usuario -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light p-4 bg-light bg-opacity-50">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalCrearLabel">
                    <i class="fa-solid fa-user-plus text-primary me-2"></i>Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('usuarios.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div>
                        <label class="form-label text-secondary small fw-bold">Nombre Completo *</label>
                        <input type="text" name="usuario" required class="form-control rounded-3" placeholder="Ej: María Fernanda Ruiz">
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Correo Electrónico *</label>
                        <input type="email" name="email" required class="form-control rounded-3" placeholder="nombre@ejemplo.com">
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Contraseña *</label>
                        <input type="password" name="password" required minlength="6" class="form-control rounded-3" placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-secondary small fw-bold">Rol *</label>
                            <select name="id_rol" required class="form-select rounded-3">
                                <option value="">Seleccionar rol...</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre ?? $rol->rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-secondary small fw-bold">Estado *</label>
                            <select name="estado" required class="form-select rounded-3">
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-3 bg-light bg-opacity-25">
                    <button type="button" class="btn btn-light rounded-3 px-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium" style="background-color: #2563eb; border-color: #2563eb;">Guardar usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light p-4 bg-light bg-opacity-50">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalEditarLabel">
                    <i class="fa-solid fa-user-pen text-primary me-2"></i>Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div>
                        <label class="form-label text-secondary small fw-bold">Nombre Completo *</label>
                        <input type="text" name="usuario" id="edit_usuario" required class="form-control rounded-3">
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Correo Electrónico *</label>
                        <input type="email" name="email" id="edit_email" required class="form-control rounded-3">
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Nueva Contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                        <input type="password" name="password" minlength="6" class="form-control rounded-3" placeholder="Dejar en blanco para mantener la actual">
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-secondary small fw-bold">Rol *</label>
                            <select name="id_rol" id="edit_id_rol" required class="form-select rounded-3">
                                <option value="">Seleccionar rol...</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre ?? $rol->rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-secondary small fw-bold">Estado *</label>
                            <select name="estado" id="edit_estado" required class="form-select rounded-3">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-3 bg-light bg-opacity-25">
                    <button type="button" class="btn btn-light rounded-3 px-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium" style="background-color: #2563eb; border-color: #2563eb;">Actualizar usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form oculto para eliminación -->
<form id="formEliminar" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Scripts: JQuery, DataTables y Eventos -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTables con buscador personalizado
        const dataTable = $('#usuariosTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                emptyTable: 'No hay usuarios registrados',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ usuarios',
                infoEmpty: 'Mostrando 0 usuarios',
                paginate: {
                    previous: '<i class="fa-solid fa-chevron-left"></i>',
                    next: '<i class="fa-solid fa-chevron-right"></i>'
                }
            },
            responsive: true,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [4] } // Desactivar orden en columna Acciones
            ]
        });

        // Vincular el campo de búsqueda "Nombre o correo" con el DataTable
        const searchInput = document.getElementById('customSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                dataTable.search(this.value).draw();
            });
        }

        // Configuración de modal de edición
        const modalEditarEl = document.getElementById('modalEditar');
        const modalEditar = new bootstrap.Modal(modalEditarEl);

        document.querySelectorAll('.btn-editar').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                const rol = this.getAttribute('data-rol');
                let estado = this.getAttribute('data-estado') || 'Activo';

                document.getElementById('formEditar').action = `/admin/usuarios/${id}`;
                document.getElementById('edit_usuario').value = name;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_id_rol').value = rol;

                estado = estado.toString().trim().toLowerCase();
                const selectEstado = document.getElementById('edit_estado');
                if (estado === '1' || estado === 'activo' || estado === 'true' || estado === 'activa') {
                    selectEstado.value = 'Activo';
                } else {
                    selectEstado.value = 'Inactivo';
                }

                modalEditar.show();
            });
        });

        // Configuración de eliminación con SweetAlert2
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: '¿Eliminar usuario?',
                    html: `¿Estás seguro de que deseas eliminar a <b>${name}</b>?<br><span class="text-muted small">Esta acción no se puede deshacer.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fa-regular fa-trash-can me-1"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('formEliminar');
                        form.action = `/admin/usuarios/${id}`;
                        form.submit();
                    }
                });
            });
        });
    });

    // Notificaciones SweetAlert2 para mensajes de sesión
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Operación exitosa!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#2563eb',
                timer: 3000,
                timerProgressBar: true
            });
        });
    @endif

    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        });
    @endif
</script>
@endsection
