@extends('layouts.app')
@section('title', 'Clientes - AgriStock')

@section('content')
<style>
    /* Estilos personalizados para la vista Clientes según diseño */
    .clientes-page-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .btn-nuevo-cliente {
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

    .btn-nuevo-cliente:hover {
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);
    }

    .card-clientes {
        background-color: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
    }

    .search-input-clientes {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 0.88rem !important;
        padding: 0.48rem 0.9rem !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .search-input-clientes::placeholder {
        color: #94a3b8;
    }

    .search-input-clientes:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        outline: none;
    }

    .table-clientes {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-clientes thead th {
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

    .table-clientes tbody tr {
        border-bottom: 1px solid #f1f5f9 !important;
        transition: background-color 0.15s ease;
    }

    .table-clientes tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table-clientes tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        padding: 1.05rem 1rem !important;
        vertical-align: middle;
    }

    .cliente-nombre {
        color: #1e293b;
        font-weight: 500;
        font-size: 0.92rem;
    }

    .cliente-texto {
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

    /* Ocultar barra de búsqueda y longitud por defecto de DataTables */
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

<div class="clientes-page-container py-3">
    
    <!-- Encabezado de página -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a; font-size: 1.85rem; letter-spacing: -0.02em;">
                Clientes
            </h2>
            <p class="mb-0" style="color: #64748b; font-size: 0.95rem;">
                Administra las cuentas y datos de los clientes.
            </p>
        </div>
        <button type="button" class="btn btn-nuevo-cliente d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa-solid fa-plus fs-6"></i>
            <span>Nuevo cliente</span>
        </button>
    </div>

    <!-- Tarjeta principal con buscador y tabla -->
    <div class="card card-clientes p-4">
        
        <!-- Fila de Búsqueda -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <label for="customSearchInput" class="mb-0 fw-medium" style="color: #64748b; font-size: 0.9rem;">Buscar</label>
            <div style="width: 320px; max-width: 100%;">
                <input type="text" id="customSearchInput" class="form-control search-input-clientes" placeholder="Nombre, CC o dirección" autocomplete="off">
            </div>
        </div>

        <!-- Tabla de Clientes -->
        <div class="table-responsive">
            <table id="clientesTable" class="table table-clientes align-middle">
                <thead>
                    <tr>
                        <th class="ps-2">NOMBRE</th>
                        <th>CC / DOCUMENTO</th>
                        <th>TELÉFONO</th>
                        <th>CORREO</th>
                        <th>DIRECCIÓN</th>
                        <th>ESTADO</th>
                        <th class="text-center" style="width: 110px;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cli)
                        @php
                            $isActivo = (bool)$cli->estado;
                        @endphp
                        <tr>
                            <td class="ps-2 cliente-nombre">
                                {{ $cli->nombre }}
                            </td>
                            <td class="cliente-texto">
                                <span class="text-muted small fw-medium me-1">{{ $cli->tipo_documento }}</span>{{ $cli->numero_documento }}
                            </td>
                            <td class="cliente-texto">
                                {{ $cli->telefono ?? '—' }}
                            </td>
                            <td class="cliente-texto">
                                {{ $cli->email ?? '—' }}
                            </td>
                            <td class="cliente-texto">
                                {{ $cli->direccion ?? '—' }}
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
                                        class="btn-table-action btn-editar-cli" 
                                        title="Editar"
                                        data-id="{{ $cli->id_cliente }}"
                                        data-nombre="{{ $cli->nombre }}"
                                        data-tipo-documento="{{ $cli->tipo_documento }}"
                                        data-numero-documento="{{ $cli->numero_documento }}"
                                        data-telefono="{{ $cli->telefono }}"
                                        data-email="{{ $cli->email }}"
                                        data-direccion="{{ $cli->direccion }}"
                                        data-estado="{{ $cli->estado }}">
                                        <i class="fa-solid fa-pen action-icon-edit"></i>
                                    </button>
                                    <button type="button" 
                                        class="btn-table-action btn-eliminar-cli" 
                                        title="Eliminar"
                                        data-id="{{ $cli->id_cliente }}"
                                        data-name="{{ $cli->nombre }}">
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

<!-- Modal Crear Cliente -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light p-4 bg-light bg-opacity-50">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalCreateLabel">
                    <i class="fa-solid fa-user-plus text-primary me-2"></i>Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('clientes.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Nombre Completo *</label>
                            <input type="text" name="nombre" id="create_nombre" required class="form-control rounded-3" placeholder="Ej: Carlos Gómez">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">Tipo Documento *</label>
                            <select name="tipo_documento" id="create_tipo_documento" required class="form-select rounded-3">
                                <option value="CC" selected>CC</option>
                                <option value="NIT">NIT</option>
                                <option value="CE">CE</option>
                                <option value="PASAPORTE">PASAPORTE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">Número Documento (CC) *</label>
                            <input type="text" name="numero_documento" id="create_numero_documento" required class="form-control rounded-3" placeholder="1075234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Teléfono</label>
                            <input type="text" name="telefono" id="create_telefono" class="form-control rounded-3" placeholder="320 123 4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" id="create_email" class="form-control rounded-3" placeholder="cliente@ejemplo.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Dirección</label>
                            <input type="text" name="direccion" id="create_direccion" class="form-control rounded-3" placeholder="Calle / Vereda / Finca">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-3 bg-light bg-opacity-25">
                    <button type="button" class="btn btn-light rounded-3 px-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium" style="background-color: #2563eb; border-color: #2563eb;">Guardar cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="modalEditCliente" tabindex="-1" aria-labelledby="modalEditClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light p-4 bg-light bg-opacity-50">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalEditClienteLabel">
                    <i class="fa-solid fa-user-pen text-primary me-2"></i>Editar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditCliente" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="id_cliente" id="edit_id_cliente">
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Nombre Completo *</label>
                            <input type="text" name="nombre" id="edit_nombre" required class="form-control rounded-3">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">Tipo Documento *</label>
                            <select name="tipo_documento" id="edit_tipo_documento" required class="form-select rounded-3">
                                <option value="CC">CC</option>
                                <option value="NIT">NIT</option>
                                <option value="CE">CE</option>
                                <option value="PASAPORTE">PASAPORTE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">Número Documento (CC) *</label>
                            <input type="text" name="numero_documento" id="edit_numero_documento" required class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Teléfono</label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" id="edit_email" class="form-control rounded-3">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-secondary small fw-bold">Dirección</label>
                            <input type="text" name="direccion" id="edit_direccion" class="form-control rounded-3">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">Estado *</label>
                            <select name="estado" id="edit_estado" required class="form-select rounded-3">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-3 bg-light bg-opacity-25">
                    <button type="button" class="btn btn-light rounded-3 px-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium" style="background-color: #2563eb; border-color: #2563eb;">Actualizar cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form oculto para eliminación -->
<form id="formEliminarCliente" method="POST" style="display: none;">
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
        const dataTable = $('#clientesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                emptyTable: 'No hay clientes registrados',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ clientes',
                infoEmpty: 'Mostrando 0 clientes',
                paginate: {
                    previous: '<i class="fa-solid fa-chevron-left"></i>',
                    next: '<i class="fa-solid fa-chevron-right"></i>'
                }
            },
            responsive: true,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [6] } // Desactivar orden en columna Acciones
            ]
        });

        // Vincular el campo de búsqueda "Nombre o documento" con el DataTable
        const searchInput = document.getElementById('customSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                dataTable.search(this.value).draw();
            });
        }

        // Configuración de modal de edición dinámica
        const modalEditClienteEl = document.getElementById('modalEditCliente');
        const modalEditCliente = new bootstrap.Modal(modalEditClienteEl);

        document.querySelectorAll('.btn-editar-cli').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('formEditCliente').action = `/admin/clientes/${id}`;
                document.getElementById('edit_id_cliente').value = id;
                document.getElementById('edit_nombre').value = this.getAttribute('data-nombre') || '';
                document.getElementById('edit_tipo_documento').value = this.getAttribute('data-tipo-documento') || 'CC';
                document.getElementById('edit_numero_documento').value = this.getAttribute('data-numero-documento') || '';
                document.getElementById('edit_telefono').value = this.getAttribute('data-telefono') || '';
                document.getElementById('edit_email').value = this.getAttribute('data-email') || '';
                document.getElementById('edit_direccion').value = this.getAttribute('data-direccion') || '';
                
                const estado = this.getAttribute('data-estado');
                document.getElementById('edit_estado').value = (estado == '1' || estado === 1 || estado === 'true') ? '1' : '0';

                modalEditCliente.show();
            });
        });

        // Configuración de eliminación con SweetAlert2
        document.querySelectorAll('.btn-eliminar-cli').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: '¿Eliminar cliente?',
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
                        const form = document.getElementById('formEliminarCliente');
                        form.action = `/admin/clientes/${id}`;
                        form.submit();
                    }
                });
            });
        });

        // Verificación en tiempo real de campos únicos para evitar duplicados
        function bindDuplicateCheck(inputEl, fieldName, getExcludeId) {
            if (!inputEl) return;
            let timer = null;
            let msgEl = document.createElement('div');
            msgEl.className = 'text-danger small mt-1 fw-medium duplicate-error-msg';
            msgEl.style.display = 'none';
            inputEl.insertAdjacentElement('afterend', msgEl);

            inputEl.addEventListener('input', function() {
                clearTimeout(timer);
                const val = this.value.trim();
                if (!val) {
                    msgEl.style.display = 'none';
                    inputEl.classList.remove('is-invalid');
                    return;
                }

                timer = setTimeout(async () => {
                    try {
                        const excludeId = getExcludeId ? getExcludeId() : null;
                        const url = new URL('/admin/clientes/check-duplicate', window.location.origin);
                        url.searchParams.set('field', fieldName);
                        url.searchParams.set('value', val);
                        if (excludeId) url.searchParams.set('exclude_id', excludeId);

                        const res = await fetch(url.toString(), {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();

                        if (data.exists) {
                            inputEl.classList.add('is-invalid');
                            msgEl.textContent = '⚠️ ' + data.message;
                            msgEl.style.display = 'block';
                        } else {
                            inputEl.classList.remove('is-invalid');
                            msgEl.style.display = 'none';
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }, 300);
            });
        }

        // Vincular en modal de creación
        bindDuplicateCheck(document.getElementById('create_nombre'), 'nombre');
        bindDuplicateCheck(document.getElementById('create_numero_documento'), 'numero_documento');
        bindDuplicateCheck(document.getElementById('create_telefono'), 'telefono');
        bindDuplicateCheck(document.getElementById('create_email'), 'email');
        bindDuplicateCheck(document.getElementById('create_direccion'), 'direccion');

        // Vincular en modal de edición
        const getEditId = () => document.getElementById('edit_id_cliente')?.value;
        bindDuplicateCheck(document.getElementById('edit_nombre'), 'nombre', getEditId);
        bindDuplicateCheck(document.getElementById('edit_numero_documento'), 'numero_documento', getEditId);
        bindDuplicateCheck(document.getElementById('edit_telefono'), 'telefono', getEditId);
        bindDuplicateCheck(document.getElementById('edit_email'), 'email', getEditId);
        bindDuplicateCheck(document.getElementById('edit_direccion'), 'direccion', getEditId);

        // Prevenir envío si hay campos duplicados
        document.querySelectorAll('#modalCreate form, #modalEditCliente form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const invalid = this.querySelectorAll('.is-invalid');
                if (invalid.length > 0) {
                    e.preventDefault();
                    e.stopPropagation();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dato duplicado',
                        text: 'Uno o más datos ingresados (como CC o dirección) ya pertenecen a otro cliente registrado. Por favor verifíquelos.',
                        confirmButtonColor: '#2563eb'
                    });
                }
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

    @if(isset($errors) && $errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'No se pudo guardar',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#ef4444'
            });
        });
    @endif
</script>
@endsection
