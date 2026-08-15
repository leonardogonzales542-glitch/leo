<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/venta.php';

$id_venta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_venta === 0) {
    header('Location: ventas.php');
    exit;
}

$ventaModel = new Venta($conn);
$venta = $ventaModel->getVentaById($id_venta);

if (!$venta) {
    echo "Venta no encontrada.";
    exit;
}

// Obtener datos de la empresa (si la tabla configuracion está disponible)
$empresa = [
    'nombre' => 'AgriStock',
    'nit' => '900.123.456-7',
    'direccion' => 'Calle Principal #12-34, Ciudad',
    'telefono' => '300 123 4567',
    'email' => 'contacto@agristock.com'
];
$res_emp = $conn->query("SELECT * FROM configuracion LIMIT 1");
if ($res_emp && $res_emp->num_rows > 0) {
    $row_emp = $res_emp->fetch_assoc();
    $empresa['nombre'] = $row_emp['nombre_empresa'] ?: $empresa['nombre'];
    $empresa['nit'] = $row_emp['nit'] ?: $empresa['nit'];
    $empresa['direccion'] = $row_emp['direccion'] ?: $empresa['direccion'];
    $empresa['telefono'] = $row_emp['telefono'] ?: $empresa['telefono'];
    $empresa['email'] = $row_emp['email'] ?: $empresa['email'];
}

$titulo = 'Factura ' . $venta['numero_factura'];
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<style>
/* Estilos específicos para impresión */
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    @page {
        margin: 0.5cm;
    }
}
</style>

<div class="d-flex flex-column gap-4 no-print" style="max-width: 900px; margin: 0 auto; width: 100%;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Detalle de Venta</h3>
            <span class="text-muted small">Visualización de Factura</span>
        </div>
        <div class="d-flex gap-2">
            <a href="ventas.php" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">
                <i class="fa-solid fa-arrow-left me-2"></i>Volver
            </a>
            <button onclick="window.print()" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-print me-2"></i>Imprimir / Descargar PDF
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mx-auto print-area" style="max-width: 900px; margin-top: 20px;">
    <div class="card-body p-5">
        
        <!-- Header Factura -->
        <div class="row border-bottom pb-4 mb-4">
            <div class="col-sm-6">
                <h2 class="fw-bold text-primary mb-1"><?= htmlspecialchars($empresa['nombre']) ?></h2>
                <div class="text-muted small">
                    <div>NIT: <?= htmlspecialchars($empresa['nit']) ?></div>
                    <div><?= htmlspecialchars($empresa['direccion']) ?></div>
                    <div>Tel: <?= htmlspecialchars($empresa['telefono']) ?></div>
                    <div><?= htmlspecialchars($empresa['email']) ?></div>
                </div>
            </div>
            <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                <h4 class="text-dark fw-bold mb-1">FACTURA DE VENTA</h4>
                <div class="text-muted mb-2">No. <span class="text-dark fw-bold"><?= $venta['numero_factura'] ?></span></div>
                <div class="small">
                    <strong>Fecha:</strong> <?= date('d/m/Y h:i A', strtotime($venta['fecha'])) ?><br>
                    <strong>Estado:</strong> 
                    <?php if($venta['estado'] == 'ACTIVA'): ?>
                        <span class="text-success fw-bold">PAGADA</span>
                    <?php else: ?>
                        <span class="text-danger fw-bold">ANULADA</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Info Cliente y Pago -->
        <div class="row border-bottom pb-4 mb-4">
            <div class="col-sm-6">
                <h6 class="fw-bold text-uppercase text-muted mb-2">Facturado a:</h6>
                <div class="text-dark">
                    <div class="fw-bold fs-5"><?= htmlspecialchars($venta['cliente']) ?></div>
                    <div>Doc: <?= htmlspecialchars($venta['numero_documento'] ?? 'N/A') ?></div>
                    <div>Dir: <?= htmlspecialchars($venta['direccion'] ?? 'N/A') ?></div>
                    <div>Tel: <?= htmlspecialchars($venta['telefono'] ?? 'N/A') ?></div>
                </div>
            </div>
            <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                <h6 class="fw-bold text-uppercase text-muted mb-2">Detalles de Pago:</h6>
                <div class="text-dark">
                    <div><strong>Método de Pago:</strong> <?= htmlspecialchars($venta['metodo_pago'] ?? 'Efectivo') ?></div>
                    <div><strong>Vendedor:</strong> <?= htmlspecialchars($venta['vendedor']) ?></div>
                </div>
            </div>
        </div>

        <!-- Tabla Productos -->
        <div class="table-responsive mb-4">
            <table class="table table-borderless">
                <thead class="border-bottom border-dark text-uppercase small fw-bold">
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-end">V. Unitario</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="border-bottom">
                    <?php foreach($venta['detalles'] as $item): ?>
                    <tr>
                        <td class="text-muted"><?= htmlspecialchars($item['codigo']) ?></td>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td class="text-center"><?= $item['cantidad'] ?></td>
                        <td class="text-end">$<?= number_format($item['precio_unitario'], 2) ?></td>
                        <td class="text-end fw-bold">$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="row">
            <div class="col-sm-6">
                <div class="bg-light p-3 rounded-3 text-muted small">
                    <p class="mb-0 fw-bold">Términos y condiciones:</p>
                    <p class="mb-0">Revise sus productos antes de retirarse. No se aceptan devoluciones de productos abiertos o en mal estado por manipulación.</p>
                </div>
            </div>
            <div class="col-sm-6">
                <table class="table table-borderless table-sm text-end">
                    <tr>
                        <td class="text-muted">Subtotal:</td>
                        <td class="fw-bold">$<?= number_format($venta['subtotal'], 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Descuento:</td>
                        <td class="fw-bold text-danger">-$<?= number_format($venta['descuento'], 2) ?></td>
                    </tr>
                    <tr class="border-top border-dark border-2 border-opacity-25 fs-5">
                        <td class="fw-bold text-dark pt-2">Total a Pagar:</td>
                        <td class="fw-bold text-primary pt-2">$<?= number_format($venta['total'], 2) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-5 pt-3 border-top text-muted small">
            <p>¡Gracias por su compra en AgriStock!</p>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
