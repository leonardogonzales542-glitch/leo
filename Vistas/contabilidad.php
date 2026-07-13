<?php
session_start();
require_once __DIR__ . '/../Modelos/Contabilidad.php';

$cont         = new Contabilidad();
$resumen      = $cont->resumenGeneral();
$porCliente   = $cont->comprasPorCliente();
$porMes       = $cont->ventasPorMes();
$topProductos = $cont->topProductos();
$porMetodo    = $cont->ventasPorMetodo();

// Historial de cliente seleccionado
$clienteId       = (int)($_GET['cliente_id'] ?? 0);
$historialCliente = $clienteId ? $cont->historialCliente($clienteId) : [];
$clienteNombre    = '';
if ($clienteId) {
    foreach ($porCliente as $c) {
        if ($c['id'] == $clienteId) { $clienteNombre = $c['nombre']; break; }
    }
}

// Datos para gráficas (JSON)
$mesesLabels  = json_encode(array_column($porMes, 'mes_label'));
$mesesTotales = json_encode(array_column($porMes, 'total'));
$metodosLabels= json_encode(array_column($porMetodo, 'metodo_pago'));
$metodosTots  = json_encode(array_column($porMetodo, 'total'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Contabilidad – TiendaInsumo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="../public/css/style.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    body { background:#f8fafc; font-family:'Inter',sans-serif; }
    .kpi-card {
      background:#fff; border-radius:16px;
      padding:24px 28px; border:1px solid #e2e8f0;
      box-shadow:0 1px 4px rgba(0,0,0,.06);
      transition:transform .2s,box-shadow .2s;
    }
    .kpi-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.09); }
    .kpi-icon { width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem; }
    .kpi-value { font-size:1.75rem;font-weight:800;letter-spacing:-.5px; }
    .table-hover tbody tr:hover { background:#f0fdf4; }
    .badge-tipo-Mayorista { background:#dbeafe;color:#1d4ed8; }
    .badge-tipo-Minorista { background:#dcfce7;color:#15803d; }
    .badge-tipo-Distribuidor { background:#fef9c3;color:#854d0e; }
    .rank-badge { width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700; }
    .chart-card { background:#fff;border-radius:16px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.06); }
    .progress-thin { height:6px; }
    .cliente-row { cursor:pointer; }
    .cliente-row:hover td { background:#f0fdf4 !important; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand d-flex align-items-center text-white" href="../dashboard.php">
      <i class="bi bi-shop text-warning me-2 fs-3"></i>
      <span class="fw-bold">Tienda</span><span class="text-warning fw-light">Insumo</span>
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto ms-3">
        <li class="nav-item"><a class="nav-link nav-link-custom" href="../dashboard.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="inventario.html"><i class="bi bi-box-seam me-1"></i> Inventario</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="ventas.html"><i class="bi bi-cart3 me-1"></i> Ventas</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="clientes.html"><i class="bi bi-people me-1"></i> Clientes</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom active" href="contabilidad.php"><i class="bi bi-calculator me-1"></i> Contabilidad</a></li>
      </ul>
      <div class="d-flex gap-2">
        <a href="../Controladores/ReporteControlador.php?tipo=contabilidad_pdf" target="_blank" class="btn btn-danger rounded-pill px-3">
          <i class="bi bi-file-earmark-pdf me-1"></i> PDF / Imprimir
        </a>
        <a href="../Controladores/ReporteControlador.php?tipo=contabilidad" class="btn btn-outline-light btn-sm rounded-pill px-3">
          <i class="bi bi-download me-1"></i> Exportar CSV
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- HEADER -->
<header class="bg-white border-bottom py-3 mb-4">
  <div class="container-fluid px-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1 small">
          <li class="breadcrumb-item"><a href="../dashboard.php">Inicio</a></li>
          <li class="breadcrumb-item active">Contabilidad</li>
        </ol>
      </nav>
      <h3 class="fw-bold mb-0"><i class="bi bi-calculator-fill text-success me-2"></i>Contabilidad de Clientes</h3>
      <p class="text-muted small mb-0">Análisis de compras, ingresos y comportamiento por cliente</p>
    </div>
    <div class="d-flex gap-2">
      <a href="../Controladores/ReporteControlador.php?tipo=contabilidad_pdf" target="_blank" class="btn btn-danger rounded-pill px-4">
        <i class="bi bi-file-earmark-pdf me-1"></i> Reporte PDF
      </a>
      <a href="../Controladores/ReporteControlador.php?tipo=contabilidad" class="btn btn-success rounded-pill px-4">
        <i class="bi bi-file-earmark-excel me-1"></i> Contabilidad CSV
      </a>
      <a href="../Controladores/ReporteControlador.php?tipo=inventario" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-file-earmark-excel me-1"></i> Inventario CSV
      </a>
      <a href="../Controladores/ReporteControlador.php?tipo=ventas" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-file-earmark-excel me-1"></i> Ventas CSV
      </a>
    </div>
        <i class="bi bi-file-earmark-excel me-1"></i> Ventas CSV
      </a>
    </div>
  </div>
</header>

<main class="container-fluid px-5 pb-5">

  <!-- KPIs -->
  <section class="row g-4 mb-5">
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold text-uppercase mb-1">Ingresos Totales</p>
            <div class="kpi-value text-success">$<?= number_format($resumen['ingresos_totales'],0,',','.') ?></div>
            <small class="text-muted">Todas las ventas completadas</small>
          </div>
          <div class="kpi-icon bg-success bg-opacity-10 text-success"><i class="bi bi-cash-stack"></i></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold text-uppercase mb-1">Ingresos Hoy</p>
            <div class="kpi-value text-primary">$<?= number_format($resumen['ingresos_hoy'],0,',','.') ?></div>
            <small class="text-muted">Ventas del día actual</small>
          </div>
          <div class="kpi-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-day"></i></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold text-uppercase mb-1">Ingresos del Mes</p>
            <div class="kpi-value text-warning">$<?= number_format($resumen['ingresos_mes'],0,',','.') ?></div>
            <small class="text-muted">Mes actual</small>
          </div>
          <div class="kpi-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-month"></i></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold text-uppercase mb-1">Total Ventas</p>
            <div class="kpi-value text-dark"><?= number_format($resumen['total_ventas'],0) ?></div>
            <small class="text-muted"><?= $resumen['clientes_compraron'] ?> clientes activos</small>
          </div>
          <div class="kpi-icon bg-dark bg-opacity-10 text-dark"><i class="bi bi-receipt"></i></div>
        </div>
      </div>
    </div>
  </section>

  <!-- GRÁFICAS -->
  <section class="row g-4 mb-5">
    <!-- Ventas por mes -->
    <div class="col-lg-8">
      <div class="chart-card">
        <h6 class="fw-bold mb-1">Ingresos por Mes</h6>
        <p class="text-muted small mb-4">Últimos 6 meses — ventas completadas</p>
        <canvas id="chartMeses" height="100"></canvas>
      </div>
    </div>
    <!-- Métodos de pago -->
    <div class="col-lg-4">
      <div class="chart-card">
        <h6 class="fw-bold mb-1">Métodos de Pago</h6>
        <p class="text-muted small mb-4">Distribución por tipo de pago</p>
        <canvas id="chartMetodos" height="200"></canvas>
        <div class="mt-3">
          <?php foreach($porMetodo as $m): ?>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small text-muted"><?= htmlspecialchars($m['metodo_pago']) ?></span>
            <span class="fw-semibold small">$<?= number_format($m['total'],0,',','.') ?> <span class="text-muted">(<?= $m['cantidad'] ?>)</span></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- TOP PRODUCTOS -->
  <section class="mb-5">
    <div class="chart-card">
      <h6 class="fw-bold mb-4"><i class="bi bi-trophy-fill text-warning me-2"></i>Top 5 Productos más Vendidos</h6>
      <div class="row g-3">
        <?php
        $maxTop = !empty($topProductos) ? max(array_column($topProductos,'total_generado')) : 1;
        $colores = ['success','primary','warning','info','secondary'];
        foreach($topProductos as $i => $tp):
          $pct = $maxTop > 0 ? round($tp['total_generado'] / $maxTop * 100) : 0;
        ?>
        <div class="col-12">
          <div class="d-flex align-items-center gap-3">
            <span class="rank-badge bg-<?= $colores[$i] ?> bg-opacity-15 text-<?= $colores[$i] ?>"><?= $i+1 ?></span>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between mb-1">
                <span class="fw-semibold small"><?= htmlspecialchars($tp['marca']) ?> — <?= htmlspecialchars($tp['nombre']) ?></span>
                <span class="small text-muted"><?= $tp['unidades_vendidas'] ?> uds · <strong class="text-dark">$<?= number_format($tp['total_generado'],0,',','.') ?></strong></span>
              </div>
              <div class="progress progress-thin rounded-pill">
                <div class="progress-bar bg-<?= $colores[$i] ?>" style="width:<?= $pct ?>%"></div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($topProductos)): ?>
        <div class="col-12 text-center text-muted py-4"><i class="bi bi-inbox fs-2"></i><p class="mt-2">Sin datos de ventas aún.</p></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- TABLA CLIENTES -->
  <section class="mb-5">
    <div class="chart-card">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h6 class="fw-bold mb-1"><i class="bi bi-people-fill text-primary me-2"></i>Compras por Cliente</h6>
          <p class="text-muted small mb-0">Haz clic en un cliente para ver su historial detallado</p>
        </div>
        <input type="text" id="buscaCliente" class="form-control form-control-sm rounded-pill" style="max-width:260px" placeholder="🔍 Buscar cliente...">
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaClientes">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Tipo</th>
              <th class="text-center">Pedidos</th>
              <th class="text-end">Total Comprado</th>
              <th class="text-center">Última Compra</th>
              <th class="text-center">Participación</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $totalGlobal = $resumen['ingresos_totales'] ?: 1;
            foreach($porCliente as $i => $c):
              $pct = $totalGlobal > 0 ? round($c['total_comprado'] / $totalGlobal * 100, 1) : 0;
            ?>
            <tr class="cliente-row" onclick="verHistorial(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['nombre'])) ?>')">
              <td class="text-muted small"><?= $i + 1 ?></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-success bg-opacity-15 text-success d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-weight:700;font-size:.9rem;">
                    <?= mb_strtoupper(mb_substr($c['nombre'],0,1)) ?>
                  </div>
                  <div>
                    <div class="fw-semibold text-dark"><?= htmlspecialchars($c['nombre']) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($c['email'] ?? '—') ?></small>
                  </div>
                </div>
              </td>
              <td><span class="badge rounded-pill badge-tipo-<?= $c['tipo'] ?> px-3 py-1"><?= $c['tipo'] ?></span></td>
              <td class="text-center fw-bold"><?= $c['total_pedidos'] ?></td>
              <td class="text-end">
                <span class="fw-bold <?= $c['total_comprado'] > 0 ? 'text-success' : 'text-muted' ?>">
                  $<?= number_format($c['total_comprado'],0,',','.') ?>
                </span>
              </td>
              <td class="text-center text-muted small">
                <?= $c['ultima_compra'] ? date('d/m/Y', strtotime($c['ultima_compra'])) : '—' ?>
              </td>
              <td style="min-width:120px">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1 progress-thin rounded-pill">
                    <div class="progress-bar bg-success" style="width:<?= $pct ?>%"></div>
                  </div>
                  <span class="small text-muted"><?= $pct ?>%</span>
                </div>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="event.stopPropagation();verHistorial(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['nombre'])) ?>')">
                  <i class="bi bi-eye me-1"></i> Ver
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- HISTORIAL DE CLIENTE (si se seleccionó) -->
  <?php if($clienteId && !empty($historialCliente)): ?>
  <section class="mb-5">
    <div class="chart-card border border-success border-opacity-25">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-success me-2"></i>Historial de: <span class="text-success"><?= htmlspecialchars($clienteNombre) ?></span></h6>
        <a href="contabilidad.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-x me-1"></i> Cerrar</a>
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Código Venta</th>
              <th>Fecha</th>
              <th>Productos</th>
              <th class="text-end">Total</th>
              <th class="text-center">Método</th>
              <th class="text-center">Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($historialCliente as $h): ?>
            <tr>
              <td class="fw-bold text-dark"><?= htmlspecialchars($h['codigo_venta']) ?></td>
              <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($h['fecha'])) ?></td>
              <td class="text-muted small" style="max-width:300px"><?= htmlspecialchars($h['productos'] ?? '—') ?></td>
              <td class="text-end fw-bold text-success">$<?= number_format($h['total'],0,',','.') ?></td>
              <td class="text-center"><span class="badge bg-light text-dark border rounded-pill"><?= htmlspecialchars($h['metodo_pago']) ?></span></td>
              <td class="text-center">
                <?php
                $estados = ['Completada'=>'success','Pendiente'=>'warning','Cancelada'=>'danger'];
                $c_est   = $estados[$h['estado']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $c_est ?>-subtle text-<?= $c_est ?> rounded-pill px-2"><?= $h['estado'] ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot class="table-light">
            <tr>
              <td colspan="3" class="fw-bold text-end">Total gastado por <?= htmlspecialchars($clienteNombre) ?>:</td>
              <td class="text-end fw-bold text-success fs-6">$<?= number_format(array_sum(array_column($historialCliente,'total')),0,',','.') ?></td>
              <td colspan="2"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </section>
  <?php endif; ?>

</main>

<!-- Modal Historial (JS click) -->
<div class="modal fade" id="modalHistorial" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="modalHistorialTitle"><i class="bi bi-clock-history me-2 text-warning"></i>Historial</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0" id="modalHistorialBody">
        <div class="text-center py-5"><div class="spinner-border text-success"></div></div>
      </div>
    </div>
  </div>
</div>

<footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary border-opacity-20">
  <div class="container-fluid px-5 text-center">
    <p class="mb-0">&copy; 2026 <span class="text-white fw-bold">TiendaInsumo</span>. Módulo de Contabilidad.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Gráfica de ingresos por mes ──────────────────────────────
new Chart(document.getElementById('chartMeses'), {
  type: 'bar',
  data: {
    labels: <?= $mesesLabels ?>,
    datasets: [{
      label: 'Ingresos ($)',
      data: <?= $mesesTotales ?>,
      backgroundColor: 'rgba(34,197,94,.25)',
      borderColor: 'rgba(34,197,94,1)',
      borderWidth: 2,
      borderRadius: 8,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: {
        ticks: {
          callback: v => '$' + Number(v).toLocaleString('es-CO')
        }
      }
    }
  }
});

// ── Gráfica de métodos de pago ────────────────────────────────
new Chart(document.getElementById('chartMetodos'), {
  type: 'doughnut',
  data: {
    labels: <?= $metodosLabels ?>,
    datasets: [{
      data: <?= $metodosTots ?>,
      backgroundColor: ['#22c55e','#3b82f6','#f59e0b','#8b5cf6','#ef4444'],
      borderWidth: 0,
    }]
  },
  options: {
    responsive: true,
    cutout: '65%',
    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
  }
});

// ── Buscador de clientes ──────────────────────────────────────
document.getElementById('buscaCliente').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#tablaClientes tbody tr').forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});

// ── Ver historial en modal ────────────────────────────────────
function verHistorial(id, nombre) {
  document.getElementById('modalHistorialTitle').innerHTML =
    '<i class="bi bi-clock-history me-2 text-warning"></i>Historial de: <strong>' + nombre + '</strong>';
  document.getElementById('modalHistorialBody').innerHTML =
    '<div class="text-center py-5"><div class="spinner-border text-success"></div></div>';
  new bootstrap.Modal(document.getElementById('modalHistorial')).show();

  fetch('contabilidad_ajax.php?cliente_id=' + id)
    .then(r => r.json())
    .then(data => {
      if (!data.ok || !data.data.length) {
        document.getElementById('modalHistorialBody').innerHTML =
          '<div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2"></i><p class="mt-2">Sin compras registradas.</p></div>';
        return;
      }
      let total = 0;
      let rows = data.data.map(h => {
        total += parseFloat(h.total);
        const est = {Completada:'success',Pendiente:'warning',Cancelada:'danger'};
        const c = est[h.estado] || 'secondary';
        return `<tr>
          <td class="fw-bold">${h.codigo_venta}</td>
          <td class="text-muted small">${new Date(h.fecha).toLocaleString('es-CO')}</td>
          <td class="text-muted small" style="max-width:280px">${h.productos || '—'}</td>
          <td class="text-end fw-bold text-success">$${parseFloat(h.total).toLocaleString('es-CO',{minimumFractionDigits:0})}</td>
          <td class="text-center"><span class="badge bg-light text-dark border rounded-pill">${h.metodo_pago}</span></td>
          <td class="text-center"><span class="badge bg-${c}-subtle text-${c} rounded-pill px-2">${h.estado}</span></td>
        </tr>`;
      }).join('');
      document.getElementById('modalHistorialBody').innerHTML = `
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Código</th><th>Fecha</th><th>Productos</th><th class="text-end">Total</th><th class="text-center">Método</th><th class="text-center">Estado</th></tr></thead>
            <tbody>${rows}</tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="3" class="text-end fw-bold">Total gastado:</td>
                <td class="text-end fw-bold text-success fs-6">$${total.toLocaleString('es-CO',{minimumFractionDigits:0})}</td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          </table>
        </div>`;
    })
    .catch(() => {
      document.getElementById('modalHistorialBody').innerHTML =
        '<div class="alert alert-danger m-3">Error al cargar historial.</div>';
    });
}
</script>
</body>
</html>
