<?php
session_start();

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['id_rol'] != 1 && $_SESSION['usuario']['id_rol'] !== '1')) {
    header('Location: ../../public/index.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/usuario.php';
require_once __DIR__ . '/../../models/rol.php';

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->obtenerTodos();
$estados = $usuarioModel->obtenerEstados();

$rolModel = new Rol();
$roles = $rolModel->obtenerTodos();

$titulo = 'Gestión de Usuarios';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="container-fluid py-2">
    <div class="row g-4">
        <!-- Main Card: User List -->
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-dark mb-1" style="font-family: var(--font-heading);">
                            <span class="text-success">Gestión</span> de Usuarios
                        </h2>
                        <p class="text-muted small mb-0">Administra las cuentas de usuario y sus privilegios de acceso al sistema.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                        Agregar Usuario
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
                    <table id="usuariosTable" class="table table-hover align-middle border-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Id</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Nombre Completo</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Email</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Rol</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase">Estado</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase text-center">Editar</th>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold text-uppercase text-center">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold text-secondary">#<?= htmlspecialchars($usuario['id_usuario']) ?></td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                            <?= strtoupper(substr($usuario['usuario'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-semibold text-dark"><?= htmlspecialchars($usuario['usuario']) ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted"><?= htmlspecialchars($usuario['email']) ?></td>
                                <td class="px-4 py-3">
                                    <?php
                                    $rol_badge = 'bg-secondary';
                                    $rol_icon = 'bi-person';
                                    $rol_name = htmlspecialchars($usuario['id_rol']);

                                    if ($usuario['id_rol'] == 'administrador' || $usuario['id_rol'] == '1') {
                                        $rol_badge = 'bg-dark text-white';
                                        $rol_icon = 'bi-shield-lock-fill text-success';
                                        $rol_name = 'Administrador';
                                    } elseif ($usuario['id_rol'] == 'vendedor' || $usuario['id_rol'] == '2') {
                                        $rol_badge = 'bg-secondary text-white';
                                        $rol_icon = 'bi-shop';
                                        $rol_name = 'Vendedor';
                                    } elseif ($usuario['id_rol'] == 'cliente' || $usuario['id_rol'] == '3') {
                                        $rol_badge = 'bg-light text-dark border border-secondary border-opacity-25';
                                        $rol_icon = 'bi-person-fill text-muted';
                                        $rol_name = 'Cliente';
                                    }
                                    ?>
                                    <span class="badge <?= $rol_badge ?> px-3 py-2 rounded-pill shadow-sm">
                                        <i class="bi <?= $rol_icon ?> me-1"></i> <?= $rol_name ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                     <?php if (strtolower($usuario['estado']) === 'activo' || $usuario['estado'] == 1 || $usuario['estado'] == '1'): ?>
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
                                        data-bs-target="#modalEditar"
                                        data-id="<?= $usuario['id_usuario'] ?>"
                                        data-name="<?= $usuario['usuario'] ?>"
                                        data-email="<?= $usuario['email'] ?>"
                                        data-rol="<?= $usuario['id_rol'] ?>"
                                        data-estado="<?= $usuario['estado'] ?>"
                                        title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center border-0 mx-auto deletebtn" data-id="<?= $usuario['id_usuario'] ?>" style="width: 35px; height: 35px;" title="Eliminar">
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

<!-- Modal Crear Usuario -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalCrearLabel" style="font-family: var(--font-heading);">
                    <i class="fa-solid fa-user-plus text-success me-2"></i>Agregar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/registerControllerAdmin.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div class="row g-3">
                        <div>
                            <label class="form-label text-secondary small fw-bold">Usuario *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="usuario" required class="form-control" placeholder="Ej: junalozada29">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Correo Electrónico *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" required class="form-control" placeholder="nombre@ejemplo.com">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" required class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Rol *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user-shield"></i></span>
                            <select name="rol" id="rolSelect" required class="form-select bg-white">
                                <option value="">Seleccione un rol...</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?= htmlspecialchars($rol['id_rol']) ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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
                    <button type="submit" class="btn btn-success">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!---------------------------------------- Modal Editar Usuario --------------------------------------->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalEditarLabel" style="font-family: var(--font-heading);">
                    <i class="fa-solid fa-user-pen text-success me-2"></i>Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/editControllerAdmin.php?accion=update" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4 d-flex flex-column gap-3">
                    <div class="row g-3">
                        <div class="col-3">
                            <label class="form-label text-secondary small fw-bold">ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="text" name="id_usuario" id="edit_id_usuario" readonly class="form-control bg-light cursor-not-allowed text-muted text-center fw-bold">
                            </div>
                        </div>
                        <div class="col-9">
                            <label class="form-label text-secondary small fw-bold">Usuario *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="usuario" id="edit_usuario" required class="form-control">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" id="edit_email" readonly class="form-control bg-light cursor-not-allowed text-muted">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-secondary small fw-bold">Rol *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user-shield"></i></span>
                            <select name="rol" id="edit_rol" required class="form-select bg-white">
                                <option value="">Seleccione un rol...</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?= htmlspecialchars($rol['id_rol']) ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                                <?php endforeach; ?>
                            </select>
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
                    <button type="submit" class="btn btn-success">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Usuario (Bootstrap modal) -->
<div class="modal fade" id="eliminar" tabindex="-1" aria-labelledby="eliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark" id="eliminarLabel">
                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Confirmar Acción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/editControllerAdmin.php?accion=delete" method="POST">
                <input type="hidden" name="id_usuario" id="delete_id_usuario">
                <div class="modal-body p-4 text-center">
                    <p class="mb-0">¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-top border-light p-4">
                    <button type="button" class="btn btn-danger rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 py-2">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('edit_id_usuario').value = id;
                document.getElementById('edit_rol').value = this.getAttribute('data-rol');
                document.getElementById('edit_usuario').value = this.getAttribute('data-name');
                document.getElementById('edit_email').value = this.getAttribute('data-email');

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
        $('#usuariosTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: false,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [5, 6] }
            ]
        });

        // Trigger dynamic delete modal
        $(document).on('click', '.deletebtn', function() {
            var id = $(this).data('id');
            $('#delete_id_usuario').val(id);
            var delModal = new bootstrap.Modal(document.getElementById('eliminar'));
            delModal.show();
        });
    });
</script>



<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
