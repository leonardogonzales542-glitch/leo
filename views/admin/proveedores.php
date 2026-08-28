<?php
session_start();

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['id_rol'] != 1 && $_SESSION['usuario']['id_rol'] !== '1')) {
    header('Location: ../../public/index.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/proveedor.php';

$proveedores = Proveedor::obtenerTodos();
$estados = Proveedor::obtenerEstados();

$titulo = 'Proveedores';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="container-fluid py-2">
    <div class="row g-4">
        <!-- Main Card: Providers List -->
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-dark mb-1" style="font-family: var(--font-heading);">
                            <span class="text-success">Gestión</span> de Proveedores
                        </h2>
                        <p class="text-muted small mb-0">Administra los proveedores de insumos agrícolas registrados en el sistema.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor">
                        <i class="fa-solid fa-plus me-2"></i>Agregar Proveedor
                    </button>
                </div>

                <?php if (isset($_SESSION['alert'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: '<?= htmlspecialchars($_SESSION['alert']['icon']) ?>',
                                title: '<?= htmlspecialchars($_SESSION['alert']['title']) ?>',
                                text: '<?= htmlspecialchars($_SESSION['alert']['text']) ?>',
                                confirmButtonColor: '#10b981',
                                background: '#1e293b',
                                color: '#fff'
                            });
                        });
                    </script>
                    <?php unset($_SESSION['alert']); ?>
                <?php endif; ?>

                <!-- Table -->
                <div class="mt-2 table-responsive">
                    <table id="proveedoresTable" class="table table-hover align-middle border-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">ID</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">NIT</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Razon Social</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Dirección</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Teléfono</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Email</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Estado</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase text-center">Editar</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase text-center">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proveedores as $proveedor): ?>
                            <tr>
                                <td class="px-4 py-3 fw-semibold text-dark"><?= htmlspecialchars($proveedor['id_proveedor']) ?></td>
                                <td class="px-4 py-3 fw-semibold text-dark"><?= htmlspecialchars($proveedor['nit']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($proveedor['razon_social']) ?></td>
                                <td class="px-4 py-3 text-muted"><?= htmlspecialchars($proveedor['direccion'] ?? '-') ?></td>
                                <td class="px-4 py-3 text-muted"><?= htmlspecialchars($proveedor['telefono'] ?? '-') ?></td>
                                <td class="px-4 py-3 text-muted"><?= htmlspecialchars($proveedor['email'] ?? '-') ?></td>
                                <td class="px-4 py-3">
                                     <?php if (strtolower($proveedor['estado']) === 'activo' || $proveedor['estado'] == 1 || $proveedor['estado'] == '1'): ?>
                                         <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-check-circle-fill me-1"></i> Activo
                                        </span> 
                                      <?php else: ?>
                                         <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-x-circle-fill me-1"></i> Inactivo
                                         </span>
                                      <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button"
                                        class="btn btn-outline-success btn-sm rounded-circle d-flex align-items-center justify-content-center border-0 mx-auto btn-editar"
                                        style="width: 35px; height: 35px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarProveedor"
                                        data-id="<?= $proveedor['id_proveedor'] ?>"
                                        data-razon_social="<?= $proveedor['razon_social'] ?>"
                                        data-email="<?= $proveedor['email'] ?>"
                                        data-telefono="<?= $proveedor['telefono'] ?>"
                                        data-direccion="<?= $proveedor['direccion'] ?>"
                                        data-nit="<?= $proveedor['nit'] ?>"
                                        data-estado="<?= $proveedor['estado'] ?>"
                                        title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center border-0 mx-auto deletebtn" data-id="<?= $proveedor['id_proveedor'] ?>" style="width: 35px; height: 35px;" title="Eliminar">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Proveedor -->
<div class="modal fade" id="modalCrearProveedor" tabindex="-1" aria-labelledby="modalCrearProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalCrearProveedorLabel" style="font-family: var(--font-heading);">
                    <i class="fa-solid fa-truck-fast text-success me-2"></i>Agregar Proveedor
                </h5>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/RegisterProveedorController.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div>
                        <label class="form-label text-secondary small fw-bold">NIT *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-id-card"></i></span>
                            <input type="text" name="nit" required class="form-control" placeholder="Ej: 123456789-0">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Razón Social *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-building"></i></span>
                            <input type="text" name="razon_social" required class="form-control" placeholder="Ej: Distribuidora Agrícola SAS">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-location-dot"></i></span>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej: Calle 26 # 45-12">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                            <input type="text" name="telefono" class="form-control" placeholder="Ej: 3001234567">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="proveedor@empresa.com">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Estado *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-toggle-on"></i></span>
                            <select name="estado" id="estadoSelect" required class="form-select bg-white">
                                <option value="">Seleccione un estado...</option>
                                <?php foreach ($estados as $val => $texto): ?>
                                    <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($texto) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-4">
                    <button type="button" class="btn btn-danger rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Proveedor -->
<div class="modal fade" id="modalEditarProveedor" tabindex="-1" aria-labelledby="modalEditarProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalEditarProveedorLabel" style="font-family: var(--font-heading);">
                    <i class="fa-solid fa-building-circle-exclamation text-success me-2"></i>Editar Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/editControllerAdmin.php?accion=updateProveedor" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <input type="hidden" name="id_proveedor" id="edit_id_proveedor">
                    <div>
                        <label class="form-label text-secondary small fw-bold">NIT *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-id-card"></i></span>
                            <input type="text" name="nit" id="edit_nit" required class="form-control">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Razón Social *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-building"></i></span>
                            <input type="text" name="razon_social" id="edit_razon_social" required class="form-control">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-location-dot"></i></span>
                            <input type="text" name="direccion" id="edit_direccion" class="form-control">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Estado *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-toggle-on"></i></span>
                            <select name="estado" id="edit_estado" required class="form-select bg-white">
                                <option value="">Seleccione un estado...</option>
                                <?php foreach ($estados as $val => $texto): ?>
                                    <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($texto) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-4">
                    <button type="button" class="btn btn-danger rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Actualizar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Proveedor -->
<div class="modal fade" id="eliminarProveedor" tabindex="-1" aria-labelledby="eliminarProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark" id="eliminarProveedorLabel">
                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Confirmar Acción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-0">¿Estás seguro de que deseas eliminar este proveedor? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-top border-light p-4">
                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btnConfirmarEliminarProveedor" class="btn btn-danger px-4 py-2">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('edit_id_proveedor').value = id;
                document.getElementById('edit_razon_social').value = this.getAttribute('data-razon_social');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_telefono').value = this.getAttribute('data-telefono');
                document.getElementById('edit_direccion').value = this.getAttribute('data-direccion');
                document.getElementById('edit_nit').value = this.getAttribute('data-nit');

                let estadoVal = this.getAttribute('data-estado');
                let selectEstado = document.getElementById('edit_estado');
                if (estadoVal !== null) {
                    estadoVal = estadoVal.toString().trim().toLowerCase();
                    if (estadoVal === '1' || estadoVal === 'activo' || estadoVal === 'true' || estadoVal === 'activa') {
                        selectEstado.value = 'Activo';
                    } else if (estadoVal === '0' || estadoVal === 'inactivo' || estadoVal === 'false' || estadoVal === 'inactiva') {
                        selectEstado.value = 'Inactivo';
                    } else {
                        selectEstado.value = estadoVal;
                    }
                }
            });
        });
    });

    // Validación Bootstrap
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<!-- JQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#proveedoresTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: false,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [6, 7] }
            ]
        });

        // Trigger dynamic delete modal
        $(document).on('click', '.deletebtn', function() {
            var id = $(this).data('id');
            $('#btnConfirmarEliminarProveedor').attr('href', '../../controllers/ProveedorController.php?accion=eliminar&id=' + id);
            var delModal = new bootstrap.Modal(document.getElementById('eliminarProveedor'));
            delModal.show();
        });
    });
</script>



<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
