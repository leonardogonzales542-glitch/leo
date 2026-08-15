<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido($conn);
$pedidos = $pedidoModel->getAllPedidos();

$titulo = 'Gestión de Pedidos';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Pedidos</h3>
            <span class="text-muted small">Panel principal e historial de pedidos</span>
        </div>
        <div>
            <a href="nuevo_pedido.php" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-plus me-2"></i>Nuevo Pedido
            </a>
        </div>
    </div>

    <!-- Historial de Pedidos -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tablaPedidos" class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="rounded-start">ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Entrega</th>
                            <th>Método Pago</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-end rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pedidos && $pedidos->num_rows > 0): ?>
                            <?php while($p = $pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-primary">#<?= $p['id_pedido'] ?></td>
                                    <td><?= date('d/m/Y h:i A', strtotime($p['fecha'])) ?></td>
                                    <td>
                                        <div class="fw-medium"><?= htmlspecialchars($p['cliente']) ?></div>
                                    </td>
                                    <td class="small text-muted"><?= htmlspecialchars($p['entrega'] ?? 'Local') ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($p['metodo_pago'] ?? 'Pendiente') ?></span>
                                    </td>
                                    <td class="fw-bold text-dark">$<?= number_format($p['total'], 2) ?></td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'bg-secondary';
                                            if ($p['estado'] == 'Pendiente') $badgeClass = 'bg-warning text-dark';
                                            if ($p['estado'] == 'Enviado') $badgeClass = 'bg-info text-dark';
                                            if ($p['estado'] == 'Entregado') $badgeClass = 'bg-success';
                                            if ($p['estado'] == 'Cancelado') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-medium"><?= htmlspecialchars($p['estado']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <button onclick="cambiarEstado(<?= $p['id_pedido'] ?>, '<?= $p['estado'] ?>')" class="btn btn-sm btn-light text-primary shadow-sm rounded-circle me-1" title="Cambiar Estado">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Cambiar Estado del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/PedidoController.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_estado">
                    <input type="hidden" name="id_pedido" id="input_id_pedido">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nuevo Estado</label>
                        <select name="estado" id="select_estado" class="form-select rounded-3 bg-light border-0">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Enviado">Enviado</option>
                            <option value="Entregado">Entregado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium shadow-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tablaPedidos').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[0, 'desc']] 
        });
    }

    <?php if(isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?= $_SESSION['success'] ?>',
            timer: 3000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $_SESSION['error'] ?>'
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
});

function cambiarEstado(id, estadoActual) {
    if (estadoActual === 'Cancelado') {
        Swal.fire('Atención', 'Un pedido cancelado no puede cambiar de estado.', 'warning');
        return;
    }
    document.getElementById('input_id_pedido').value = id;
    document.getElementById('select_estado').value = estadoActual;
    var myModal = new bootstrap.Modal(document.getElementById('modalEstado'));
    myModal.show();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
