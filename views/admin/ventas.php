<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/venta.php';

$ventaModel = new Venta($conn);
$ventas = $ventaModel->getAllVentas();
$stats = $ventaModel->getEstadisticas();

$titulo = 'Gestión de Ventas';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Ventas</h3>
            <span class="text-muted small">Panel principal y estadisticas de ventas</span>
        </div>
        <div>
            <a href="nueva_venta.php" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-plus me-2"></i>Nueva Venta
            </a>
        </div>
    </div>

    <!-- Panel de Estadísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid var(--primary) !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Ventas de Hoy</h6>
                    <h3 class="fw-bold text-dark mb-0">$<?= number_format($stats['ventas_hoy'], 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Ventas del Mes</h6>
                    <h3 class="fw-bold text-dark mb-0">$<?= number_format($stats['ventas_mes'], 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid #0dcaf0 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Productos Vendidos</h6>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['productos_vendidos'], 0) ?> Unds</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Total Fiado (Por cobrar)</h6>
                    <h3 class="fw-bold text-danger mb-0">$<?= number_format($stats['total_fiado'], 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Ventas -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tablaVentas" class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="rounded-start">Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Método</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-end rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($ventas && $ventas->num_rows > 0): ?>
                            <?php while($v = $ventas->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($v['numero_factura']) ?></td>
                                    <td><?= date('d/m/Y h:i A', strtotime($v['fecha'])) ?></td>
                                    <td>
                                        <div class="fw-medium"><?= htmlspecialchars($v['cliente']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($v['metodo_pago'] ?? 'N/A') ?></span>
                                    </td>
                                    <td class="fw-bold text-dark">$<?= number_format($v['total'], 2) ?></td>
                                    <td>
                                        <?php if($v['estado'] == 'ACTIVA'): ?>
                                            <span class="badge bg-success rounded-pill px-3 py-2 fw-medium"><i class="fa-solid fa-check-circle me-1"></i>Activa</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium"><i class="fa-solid fa-times-circle me-1"></i>Anulada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="factura.php?id=<?= $v['id_venta'] ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-circle me-1" title="Ver Factura">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                        <?php if($v['estado'] == 'ACTIVA'): ?>
                                            <button onclick="confirmarAnulacion(<?= $v['id_venta'] ?>)" class="btn btn-sm btn-light text-danger shadow-sm rounded-circle" title="Anular Venta">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tablaVentas').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[1, 'desc']] // Ordenar por fecha descendente
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

function confirmarAnulacion(id) {
    Swal.fire({
        title: '¿Anular esta venta?',
        text: "La venta quedará anulada y los productos regresarán al inventario.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, anular venta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../../controllers/VentaController.php?action=anular&id=' + id;
        }
    })
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
