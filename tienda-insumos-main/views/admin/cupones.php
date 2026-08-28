<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/cupon.php';

$cuponModel = new Cupon($conn);
$cupones = $cuponModel->getCupones();

$modulo = "Cupones";
$titulo = 'Gestión de Cupones';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fw-bold text-body mb-0">Cupones de Descuento</h3>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCreate" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
            <i class="fa-solid fa-plus me-2"></i>Nuevo Cupón
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">CÓDIGO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">TIPO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">DESCUENTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">VIGENCIA</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.85rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cupones && $cupones->num_rows > 0): ?>
                        <?php while($cup = $cupones->fetch_assoc()): ?>
                            <tr class="border-bottom">
                                <td class="px-4 py-3 fw-bold text-body"><?= htmlspecialchars($cup['codigo']) ?></td>
                                <td class="px-4 py-3 text-body"><?= htmlspecialchars($cup['tipo']) ?></td>
                                <td class="px-4 py-3 text-body fw-semibold">
                                    <?= $cup['tipo'] == 'Porcentaje' ? $cup['descuento'].'%' : '$'.number_format($cup['descuento'], 2) ?>
                                </td>
                                <td class="px-4 py-3 text-muted small">
                                    <?= $cup['fecha_inicio'] ? date('d/m/Y', strtotime($cup['fecha_inicio'])) : 'N/A' ?> - 
                                    <?= $cup['fecha_fin'] ? date('d/m/Y', strtotime($cup['fecha_fin'])) : 'N/A' ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($cup['estado']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button type="button" class="btn btn-sm btn-light border text-primary me-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $cup['id_cupon'] ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="../../controllers/CuponController.php?action=delete&id=<?= $cup['id_cupon'] ?>" class="btn btn-sm btn-light border text-danger shadow-sm rounded-3" onclick="return confirm('¿Estás seguro de eliminar este cupón?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEdit<?= $cup['id_cupon'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom bg-light rounded-top-4">
                                            <h5 class="modal-title fw-bold text-body">Editar Cupón</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="../../controllers/CuponController.php" method="POST">
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id_cupon" value="<?= $cup['id_cupon'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-muted small">Código del Cupón</label>
                                                    <input type="text" class="form-control bg-light rounded-3" name="codigo" value="<?= htmlspecialchars($cup['codigo']) ?>" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-muted small">Tipo de Descuento</label>
                                                        <select class="form-select bg-light rounded-3" name="tipo" required>
                                                            <option value="Porcentaje" <?= $cup['tipo'] == 'Porcentaje' ? 'selected' : '' ?>>Porcentaje (%)</option>
                                                            <option value="Fijo" <?= $cup['tipo'] == 'Fijo' ? 'selected' : '' ?>>Monto Fijo ($)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-muted small">Valor del Descuento</label>
                                                        <input type="number" step="0.01" class="form-control bg-light rounded-3" name="descuento" value="<?= $cup['descuento'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-muted small">Fecha de Inicio</label>
                                                        <input type="date" class="form-control bg-light rounded-3" name="fecha_inicio" value="<?= $cup['fecha_inicio'] ? date('Y-m-d', strtotime($cup['fecha_inicio'])) : '' ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-muted small">Fecha de Fin</label>
                                                        <input type="date" class="form-control bg-light rounded-3" name="fecha_fin" value="<?= $cup['fecha_fin'] ? date('Y-m-d', strtotime($cup['fecha_fin'])) : '' ?>">
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch mt-3">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado<?= $cup['id_cupon'] ?>" <?= $cup['estado'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label fw-semibold text-body" for="estado<?= $cup['id_cupon'] ?>">Cupón Activo</label>
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-ticket fs-1 opacity-25 mb-3 d-block"></i>
                                No hay cupones registrados. Crea tu primer cupón para promociones.
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom bg-light rounded-top-4">
                <h5 class="modal-title fw-bold text-body">Nuevo Cupón</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/CuponController.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Código del Cupón</label>
                        <input type="text" class="form-control bg-light rounded-3" name="codigo" placeholder="Ej. VERANO2026" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted small">Tipo de Descuento</label>
                            <select class="form-select bg-light rounded-3" name="tipo" required>
                                <option value="Porcentaje">Porcentaje (%)</option>
                                <option value="Fijo">Monto Fijo ($)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted small">Valor del Descuento</label>
                            <input type="number" step="0.01" class="form-control bg-light rounded-3" name="descuento" placeholder="0.00" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted small">Fecha de Inicio (Opcional)</label>
                            <input type="date" class="form-control bg-light rounded-3" name="fecha_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted small">Fecha de Fin (Opcional)</label>
                            <input type="date" class="form-control bg-light rounded-3" name="fecha_fin">
                        </div>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estadoNuevo" checked>
                        <label class="form-check-label fw-semibold text-body" for="estadoNuevo">Cupón Activo</label>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Crear Cupón</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
