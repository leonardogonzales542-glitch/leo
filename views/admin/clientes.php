<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/cliente.php';

$clienteModel = new Cliente($conn);
$clientes = $clienteModel->getAll();

$titulo = 'Gestión de Clientes';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fw-bold text-dark mb-0">Directorio de Clientes</h3>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" data-bs-toggle="modal" data-bs-target="#modalCreate" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
            <i class="fa-solid fa-user-plus me-2"></i>Nuevo Cliente
        </button>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CLIENTE</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">DOCUMENTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CONTACTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.8rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($clientes && $clientes->num_rows > 0): ?>
                        <?php while($cli = $clientes->fetch_assoc()): ?>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                            <?= strtoupper(substr($cli['nombre'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($cli['nombre']) ?></h6>
                                            <span class="text-muted small"><?= htmlspecialchars($cli['direccion'] ?? 'Sin dirección') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-dark fw-medium" style="font-size: 0.85rem;">
                                    <span class="text-muted"><?= htmlspecialchars($cli['tipo_documento']) ?></span><br>
                                    <?= htmlspecialchars($cli['numero_documento']) ?>
                                </td>
                                <td class="px-4 py-3 text-dark" style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-phone text-muted me-2" style="width: 15px;"></i><?= htmlspecialchars($cli['telefono'] ?? 'N/A') ?><br>
                                    <i class="fa-solid fa-envelope text-muted me-2" style="width: 15px;"></i><?= htmlspecialchars($cli['email'] ?? 'N/A') ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($cli['estado']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button type="button" class="btn btn-sm btn-light border text-primary me-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $cli['id_cliente'] ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="../../controllers/ClienteController.php?action=delete&id=<?= $cli['id_cliente'] ?>" class="btn btn-sm btn-light border text-danger shadow-sm rounded-3" onclick="return confirm('¿Eliminar cliente <?= htmlspecialchars($cli['nombre']) ?>?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEdit<?= $cli['id_cliente'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom bg-light rounded-top-4">
                                            <h5 class="modal-title fw-bold">Editar Cliente</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="../../controllers/ClienteController.php" method="POST">
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id_cliente" value="<?= $cli['id_cliente'] ?>">
                                                
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Nombre Completo *</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="nombre" value="<?= htmlspecialchars($cli['nombre']) ?>" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold text-muted small">Tipo Doc. *</label>
                                                        <select class="form-select bg-light rounded-3" name="tipo_documento" required>
                                                            <option value="CC" <?= $cli['tipo_documento'] == 'CC' ? 'selected' : '' ?>>CC</option>
                                                            <option value="NIT" <?= $cli['tipo_documento'] == 'NIT' ? 'selected' : '' ?>>NIT</option>
                                                            <option value="CE" <?= $cli['tipo_documento'] == 'CE' ? 'selected' : '' ?>>CE</option>
                                                            <option value="PASAPORTE" <?= $cli['tipo_documento'] == 'PASAPORTE' ? 'selected' : '' ?>>PASAPORTE</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold text-muted small">Número *</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="numero_documento" value="<?= htmlspecialchars($cli['numero_documento']) ?>" required>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Teléfono</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="telefono" value="<?= htmlspecialchars($cli['telefono']) ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Correo Electrónico</label>
                                                        <input type="email" class="form-control bg-light rounded-3" name="email" value="<?= htmlspecialchars($cli['email']) ?>">
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold text-muted small">Dirección</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="direccion" value="<?= htmlspecialchars($cli['direccion']) ?>">
                                                    </div>
                                                    
                                                    <div class="col-12 mt-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado_cli<?= $cli['id_cliente'] ?>" <?= $cli['estado'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label fw-semibold text-dark" for="estado_cli<?= $cli['id_cliente'] ?>">Cliente Activo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light rounded-bottom-4">
                                                <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fs-1 opacity-25 mb-3 d-block"></i>
                                No hay clientes registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/ClienteController.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted small">Nombre Completo / Razón Social *</label>
                            <input type="text" class="form-control bg-light rounded-3" name="nombre" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">Tipo Doc. *</label>
                            <select class="form-select bg-light rounded-3" name="tipo_documento" required>
                                <option value="CC">CC</option>
                                <option value="NIT">NIT</option>
                                <option value="CE">CE</option>
                                <option value="PASAPORTE">PASAPORTE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">Número *</label>
                            <input type="text" class="form-control bg-light rounded-3" name="numero_documento" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted small">Teléfono</label>
                            <input type="text" class="form-control bg-light rounded-3" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted small">Correo Electrónico</label>
                            <input type="email" class="form-control bg-light rounded-3" name="email">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small">Dirección</label>
                            <input type="text" class="form-control bg-light rounded-3" name="direccion">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Registrar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
