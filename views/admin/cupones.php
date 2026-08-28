<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../public/index.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/cupon.php';

$cuponModel = new Cupon($conn);
$cupones = $cuponModel->getCupones();

$modulo = "Cupones";
$titulo = $modulo;
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-0"><?= $modulo ?></h3>
            <span class="text-muted small">Gestiona los cupones de descuento</span>
        </div>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCreate" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
            <i class="fa-solid fa-plus me-2"></i>Nuevo Cupón
        </button>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?= $_SESSION['alert']['type'] ?>',
                    title: '<?= $_SESSION['alert']['title'] ?>',
                    text: '<?= $_SESSION['alert']['message'] ?>',
                    confirmButtonColor: 'var(--primary)'
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">CÓDIGO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">DESCUENTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">VIGENCIA</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.85rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cupones && $cupones->num_rows > 0): ?>
                        <?php while($cupon = $cupones->fetch_assoc()): ?>
                            <tr class="border-bottom">
                                <td class="px-4 py-3 fw-bold text-dark"><?= htmlspecialchars($cupon['codigo']) ?></td>
                                <td class="px-4 py-3 fw-semibold text-dark">
                                    <?= htmlspecialchars($cupon['descuento']) ?><?= $cupon['tipo'] == 'Porcentaje' ? '%' : ' $' ?>
                                    <br><small class="text-muted fw-normal"><?= htmlspecialchars($cupon['tipo']) ?></small>
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    <small class="d-block"><strong>Inicio:</strong> <?= !empty($cupon['fecha_inicio']) ? date('d/m/Y H:i', strtotime($cupon['fecha_inicio'])) : 'N/A' ?></small>
                                    <small class="d-block"><strong>Fin:</strong> <?= !empty($cupon['fecha_fin']) ? date('d/m/Y H:i', strtotime($cupon['fecha_fin'])) : 'N/A' ?></small>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($cupon['estado']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button type="button" class="btn btn-sm btn-light border text-primary me-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $cupon['id_cupon'] ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="../../controllers/CuponController.php?action=delete&id=<?= $cupon['id_cupon'] ?>" class="btn btn-sm btn-light border text-danger shadow-sm rounded-3 btn-delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEdit<?= $cupon['id_cupon'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom bg-light rounded-top-4">
                                            <h5 class="modal-title fw-bold">Editar Cupón</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="../../controllers/CuponController.php" method="POST">
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id_cupon" value="<?= $cupon['id_cupon'] ?>">
                                                
                                                <?php require __DIR__ . '/cupon_form.php'; ?>
                                                
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
                                <i class="fa-solid fa-ticket fs-1 opacity-25 mb-3 d-block"></i>
                                No hay cupones registrados. Comienza agregando uno nuevo.
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
                <h5 class="modal-title fw-bold">Nuevo Cupón</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/CuponController.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="create">
                    
                    <?php 
                    $cupon = null;
                    require __DIR__ . '/cupon_form.php'; 
                    ?>
                    
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estadoCreate" checked>
                        <label class="form-check-label fw-semibold" for="estadoCreate">Cupón activo</label>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
