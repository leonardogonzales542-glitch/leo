<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';

// Manejo de Fechas para Filtro
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

// Validación de fechas
if ($fecha_inicio > $fecha_fin) {
    $temp = $fecha_inicio;
    $fecha_inicio = $fecha_fin;
    $fecha_fin = $temp;
}

// 1. Total Ingresos (Ventas)
$sqlVentas = "SELECT SUM(total) as monto, COUNT(id_venta) as cantidad FROM ventas WHERE estado = 'ACTIVA' AND DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
$resVentas = $conn->query($sqlVentas)->fetch_assoc();
$totalIngresos = $resVentas['monto'] ?? 0;
$totalVentas = $resVentas['cantidad'] ?? 0;

// 2. Total Pedidos (Completados o Enviados, pero para generalizar, todo excepto Cancelado)
$sqlPedidos = "SELECT SUM(total) as monto, COUNT(id_pedido) as cantidad FROM pedidos WHERE estado != 'Cancelado' AND DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
$resPedidos = $conn->query($sqlPedidos)->fetch_assoc();
$totalPedidos = $resPedidos['cantidad'] ?? 0;

// 3. Productos más vendidos (Top 5)
$sqlTopProductos = "SELECT p.nombre, SUM(d.cantidad) as total_vendido 
                    FROM detalle_ventas d 
                    INNER JOIN ventas v ON d.id_venta = v.id_venta 
                    INNER JOIN productos p ON d.id_producto = p.id_producto 
                    WHERE v.estado = 'ACTIVA' AND DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                    GROUP BY d.id_producto 
                    ORDER BY total_vendido DESC LIMIT 5";
$topProductos = $conn->query($sqlTopProductos);

// 4. Datos para el gráfico: Ingresos por Día (Ventas)
$sqlGrafico = "SELECT DATE(fecha) as fecha_dia, SUM(total) as total_dia 
               FROM ventas 
               WHERE estado = 'ACTIVA' AND DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
               GROUP BY DATE(fecha) 
               ORDER BY DATE(fecha) ASC";
$datosGrafico = $conn->query($sqlGrafico);

$fechas_chart = [];
$totales_chart = [];

if ($datosGrafico && $datosGrafico->num_rows > 0) {
    while($row = $datosGrafico->fetch_assoc()) {
        $fechas_chart[] = date('d M', strtotime($row['fecha_dia']));
        $totales_chart[] = $row['total_dia'];
    }
}

$titulo = 'Reportes y Estadísticas';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<!-- Importar Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Reportes</h3>
            <span class="text-muted small">Análisis de rendimiento y ventas</span>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-light border shadow-sm rounded-3 px-3 fw-medium text-secondary hover-primary transition-all">
                <i class="fa-solid fa-print me-2"></i>Imprimir Reporte
            </button>
        </div>
    </div>

    <!-- Filtro de Fechas -->
    <div class="card border-0 shadow-sm rounded-4 mb-2">
        <div class="card-body p-4 d-print-none">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control rounded-3 bg-light border-0" value="<?= $fecha_inicio ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control rounded-3 bg-light border-0" value="<?= $fecha_fin ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary rounded-3 w-100 fw-medium shadow-sm" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                        <i class="fa-solid fa-filter me-2"></i>Aplicar Filtro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tarjetas de Resumen -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="border-left: 5px solid var(--primary) !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-dollar-sign fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-bold text-uppercase small mb-1">Total Ingresos (Ventas)</h6>
                <h3 class="fw-bold text-dark mb-0">$<?= number_format($totalIngresos, 2) ?></h3>
                <span class="text-muted small"><?= $totalVentas ?> transacciones</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="border-left: 5px solid #198754 !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-box-open fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-bold text-uppercase small mb-1">Pedidos Gestionados</h6>
                <h3 class="fw-bold text-dark mb-0"><?= $totalPedidos ?></h3>
                <span class="text-muted small">Excluyendo cancelados</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="border-left: 5px solid #0dcaf0 !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-chart-line fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-bold text-uppercase small mb-1">Promedio Diario (Ventas)</h6>
                <?php 
                    $dias = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / (60 * 60 * 24) + 1;
                    $promedio = $dias > 0 ? $totalIngresos / $dias : 0;
                ?>
                <h3 class="fw-bold text-dark mb-0">$<?= number_format($promedio, 2) ?></h3>
                <span class="text-muted small">En el rango seleccionado</span>
            </div>
        </div>
    </div>

    <!-- Gráfico y Top Productos -->
    <div class="row g-4 mt-1 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Evolución de Ingresos</h5>
                    <?php if (count($fechas_chart) > 0): ?>
                        <div style="height: 300px;">
                            <canvas id="ingresosChart"></canvas>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary border-0 bg-light text-center py-5">
                            <i class="fa-solid fa-inbox fs-1 text-muted mb-3 opacity-50"></i>
                            <h6 class="fw-bold text-muted mb-0">No hay datos de ventas en este rango de fechas.</h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Top 5 Productos Vendidos</h5>
                    
                    <?php if ($topProductos && $topProductos->num_rows > 0): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php $i = 1; while($p = $topProductos->fetch_assoc()): ?>
                                <div class="d-flex align-items-center p-3 rounded-3 bg-light">
                                    <div class="fw-bold fs-5 me-3 <?= $i <= 3 ? 'text-primary' : 'text-muted' ?>">#<?= $i ?></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($p['nombre']) ?></h6>
                                        <span class="text-muted small"><?= intval($p['total_vendido']) ?> unidades</span>
                                    </div>
                                </div>
                            <?php $i++; endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary border-0 bg-light text-center py-4">
                            <span class="text-muted small">Sin información de productos.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (count($fechas_chart) > 0): ?>
    const ctx = document.getElementById('ingresosChart').getContext('2d');
    
    // Gradiente para el área
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // Color primary con opacidad (usando el color del theme)
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($fechas_chart) ?>,
            datasets: [{
                label: 'Ingresos ($)',
                data: <?= json_encode($totales_chart) ?>,
                borderColor: '#10b981', // var(--primary) en la paleta del proyecto
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Curva suave
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
    <?php endif; ?>
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    /* Solo hacemos visible el contenedor principal del reporte */
    .d-flex.flex-column.gap-4, .d-flex.flex-column.gap-4 * {
        visibility: visible;
    }
    .d-print-none {
        display: none !important;
    }
    .d-flex.flex-column.gap-4 {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
