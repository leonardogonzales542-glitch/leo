<?php
require_once __DIR__ . '/config/auth.php';
requireLogin('login.php');

require_once __DIR__ . '/Modelos/Producto.php';
require_once __DIR__ . '/Modelos/Venta.php';
require_once __DIR__ . '/Modelos/Cliente.php';

// Valores por defecto en caso de error de BD
$totalProductos  = 0;
$alertasDetalle  = [];
$totalAlertas    = 0;
$ventasHoy       = 0;
$clientesActivos = 0;
$todosClientes   = [];
$todosProductos  = [];
$todasVentas     = [];
$db_error        = '';

try {
    $modelProducto = new Producto();
    $modelVenta    = new Venta();
    $modelCliente  = new Cliente();

    $totalProductos  = $modelProducto->totalProductos();
    $alertasDetalle  = $modelProducto->alertasStock();
    $totalAlertas    = count($alertasDetalle);
    $ventasHoy       = $modelVenta->ventasHoy();
    $clientesActivos = $modelCliente->totalActivos();
    $todosClientes   = $modelCliente->getAll();
    $todosProductos  = $modelProducto->getAll();
    $todasVentas     = $modelVenta->getAll();
} catch (Exception $e) {
    $db_error = $e->getMessage();
    error_log('[Dashboard] Error BD: ' . $e->getMessage());
}

$meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
$fechaActivo = date('j') . ' ' . $meses[date('n')-1] . ' ' . date('Y') . ' - ' . date('H:i:s');

// Manejar error de autorización
$auth_error = '';
if (isset($_SESSION['auth_error'])) {
    $auth_error = $_SESSION['auth_error'];
    unset($_SESSION['auth_error']);
}
?>
<?php if ($db_error): ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <title>Error de Base de Datos – TiendaInsumo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Inter',sans-serif;background:#f0f4f8;display:flex;align-items:center;justify-content:center;min-height:100vh}
    .card{background:#fff;border-radius:16px;padding:40px;max-width:560px;width:100%;box-shadow:0 8px 32px rgba(0,0,0,.12);text-align:center}
    .icon{font-size:3rem;margin-bottom:16px}
    h2{color:#dc2626;margin-bottom:12px}
    p{color:#6b7280;margin-bottom:8px;line-height:1.6}
    code{background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:.9em}
    .steps{text-align:left;background:#f9fafb;border-radius:10px;padding:20px;margin:20px 0}
    .steps li{margin:8px 0;color:#374151}
    .btn{display:inline-block;margin-top:16px;padding:12px 28px;background:#16a34a;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;transition:.2s}
    .btn:hover{background:#15803d}
    .err-detail{background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;margin:16px 0;font-size:.85rem;color:#dc2626;text-align:left;word-break:break-all}
  </style>
</head>
<body>
<div class="card">
  <div class="icon">🔌</div>
  <h2>Error de conexión a la Base de Datos</h2>
  <p>El sistema no puede conectarse a MySQL. Sigue estos pasos:</p>

  <div class="err-detail"><?= htmlspecialchars($db_error) ?></div>

  <ol class="steps">
    <li>Abre <strong>Laragon</strong> y verifica que MySQL esté <span style="color:#16a34a">● activo</span></li>
    <li>Abre en el navegador: <code>localhost:8080/tiendainsumo/setup.php</code></li>
    <li>El setup creará la base de datos automáticamente</li>
    <li>Regresa a esta página</li>
  </ol>

  <a href="setup.php" class="btn">⚙ Ir a Setup</a>
  &nbsp;
  <a href="login.php" class="btn" style="background:#6b7280">← Login</a>
</div>
</body>
</html>
<?php endif; ?>
<?php if (!$db_error): ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TiendaInsumo – Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="public/css/style.css">
  <style>
    .metric-card { cursor:pointer; transition: transform .2s, box-shadow .2s; }
    .metric-card:hover { transform:translateY(-4px); box-shadow:0 12px 24px rgba(0,0,0,.1)!important; }
    .action-quick-card { cursor:pointer; }
    .toast-container { position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; }
    .badge-alerta { animation: pulse-red 1.5s infinite; }
    @keyframes pulse-red {
      0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
      50%      { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand d-flex align-items-center text-white" href="dashboard.php">
      <i class="bi bi-shop text-warning me-2 fs-3"></i>
      <span class="fw-bold">Tienda</span><span class="text-warning fw-light">Insumo</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
        <li class="nav-item"><a class="nav-link nav-link-custom active" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="Vistas/inventario.php"><i class="bi bi-box-seam me-1"></i> Inventario</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="Vistas/ventas.php"><i class="bi bi-cart3 me-1"></i> Ventas</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="Vistas/clientes.php"><i class="bi bi-people me-1"></i> Clientes</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="Vistas/contabilidad_full.php"><i class="bi bi-calculator me-1"></i> Contabilidad</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="Vistas/estadisticas.php"><i class="bi bi-bar-chart-line me-1"></i> Estadísticas</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <div class="dropdown ms-1">
          <a href="#" class="d-block link-light text-decoration-none dropdown-toggle d-flex align-items-center gap-2" id="dropUser" data-bs-toggle="dropdown">
            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&h=100&q=80" alt="avatar" width="38" height="38" class="rounded-circle border border-2 border-warning">
            <span class="d-none d-md-inline text-white small fw-medium"><?= htmlspecialchars($_SESSION['user_nombre']) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius:.75rem;">
            <li><span class="dropdown-item-text fw-semibold text-dark"><?= htmlspecialchars($_SESSION['user_nombre']) ?></span></li>
            <li><span class="dropdown-item-text text-muted small" style="font-size:0.75rem;"><?= ucfirst(htmlspecialchars($_SESSION['user_rol'])) ?></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- HERO -->
<header class="hero-section">
  <div class="container-fluid px-5">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <span class="badge bg-warning-subtle text-warning rounded-pill mb-3">Sistema de inventario y ventas</span>
        <h1 class="display-5 fw-bold mb-3">Panel Administrativo de Stock y Ventas</h1>
        <p class="lead text-white-50 mb-4">Control total y monitoreo de existencias de insumos agrícolas y productos.</p>
      </div>
      <div class="col-lg-5 text-lg-end">
        <div class="status-pill bg-white bg-opacity-15 border border-white border-opacity-25 text-white rounded-pill d-inline-flex align-items-center px-4 py-2">
          <span class="status-dot"></span>
          <span>Sistema Activo: <?= $fechaActivo ?></span>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- MAIN -->
<main class="container-fluid px-5 pb-5">

  <?php if (!empty($auth_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($auth_error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- MÉTRICAS -->
  <section class="row g-4 mb-5">

    <!-- Productos en catálogo -->
    <div class="col-md-6 col-lg-3">
      <div class="metric-card metric-primary" data-bs-toggle="modal" data-bs-target="#modalInventario" title="Ver inventario completo">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-uppercase text-muted fw-semibold small">Productos en Catálogo</span>
            <h2 class="fw-bold mt-1 mb-0"><?= $totalProductos ?></h2>
          </div>
          <div class="metric-icon-wrapper"><i class="bi bi-box-seam-fill"></i></div>
        </div>
        <div class="mt-3 small text-muted">Haz clic para ver el inventario</div>
      </div>
    </div>

    <!-- Alertas de stock -->
    <div class="col-md-6 col-lg-3">
      <div class="metric-card metric-danger" data-bs-toggle="modal" data-bs-target="#modalAlertas" title="Ver alertas de stock">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-uppercase text-muted fw-semibold small">Alertas de Stock</span>
            <h2 class="fw-bold mt-1 mb-0 text-danger">
              <?= $totalAlertas ?>
              <?php if($totalAlertas > 0): ?>
                <span class="badge bg-danger badge-alerta ms-1" style="font-size:.6rem;">!</span>
              <?php endif; ?>
            </h2>
          </div>
          <div class="metric-icon-wrapper"><i class="bi bi-exclamation-triangle-fill"></i></div>
        </div>
        <div class="mt-3 small text-danger"><?= $totalAlertas > 0 ? 'Requiere reabastecimiento urgente' : 'Stock en niveles óptimos' ?></div>
      </div>
    </div>

    <!-- Ventas de hoy -->
    <div class="col-md-6 col-lg-3">
      <div class="metric-card metric-success" data-bs-toggle="modal" data-bs-target="#modalVentas" title="Ver ventas del día">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-uppercase text-muted fw-semibold small">Ventas de Hoy</span>
            <h2 class="fw-bold mt-1 mb-0 text-success">$<?= number_format($ventasHoy, 0, ',', '.') ?></h2>
          </div>
          <div class="metric-icon-wrapper"><i class="bi bi-currency-dollar"></i></div>
        </div>
        <div class="mt-3 small text-muted">Total vendido hoy — Haz clic para ver</div>
      </div>
    </div>

    <!-- Clientes activos -->
    <div class="col-md-6 col-lg-3">
      <div class="metric-card metric-info" data-bs-toggle="modal" data-bs-target="#modalClientes" title="Ver clientes">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-uppercase text-muted fw-semibold small">Clientes Activos</span>
            <h2 class="fw-bold mt-1 mb-0"><?= $clientesActivos ?></h2>
          </div>
          <div class="metric-icon-wrapper"><i class="bi bi-people-fill"></i></div>
        </div>
        <div class="mt-3 small text-muted">Haz clic para ver el directorio</div>
      </div>
    </div>

  </section>

  <!-- ACCIONES RÁPIDAS -->
  <section class="mb-5">
    <h4 class="fw-bold mb-4 d-flex align-items-center">
      <i class="bi bi-lightning-charge-fill text-warning me-2"></i> Acciones Rápidas del Sistema
    </h4>
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <div class="action-btn-card action-quick-card" data-bs-toggle="modal" data-bs-target="#modalNuevaVenta">
          <i class="bi bi-cart-plus"></i>
          <h6 class="fw-bold mb-1">Nueva Venta</h6>
          <p class="text-muted small mb-0">Registrar salida de stock</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="action-btn-card action-quick-card" data-bs-toggle="modal" data-bs-target="#modalAgregarInsumo">
          <i class="bi bi-plus-circle"></i>
          <h6 class="fw-bold mb-1">Agregar Insumo</h6>
          <p class="text-muted small mb-0">Ingresar nuevo producto</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="action-btn-card action-quick-card" id="btnReporte" onclick="window.location.href='Vistas/contabilidad_full.php'">
          <i class="bi bi-calculator-fill"></i>
          <h6 class="fw-bold mb-1">Contabilidad</h6>
          <p class="text-muted small mb-0">Ingresos, gastos, CxC, CxP</p>
        </div>
      </div>
      <div class="col-6 col-md-3" style="display:none"><!-- reporte removido --></div>
      <div class="col-6 col-md-3">
        <div class="action-btn-card action-quick-card" data-bs-toggle="modal" data-bs-target="#modalRegistrarCliente">
          <i class="bi bi-person-plus"></i>
          <h6 class="fw-bold mb-1">Registrar Cliente</h6>
          <p class="text-muted small mb-0">Crear perfil de comprador</p>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- ===================== MODALES ===================== -->

<!-- Modal: Inventario completo -->
<div class="modal fade" id="modalInventario" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2 text-warning"></i> Inventario Completo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Código</th><th>Marca / Producto</th><th>Cat.</th><th class="text-center">Peso</th><th class="text-end">Costo</th><th class="text-end">P.Venta</th><th class="text-center">Stock</th><th class="text-center">Estado</th></tr></thead>
            <tbody>
              <?php foreach($todosProductos as $p): ?>
              <tr>
                <td class="text-muted small"><?= htmlspecialchars($p['codigo']) ?></td>
                <td><b><?= htmlspecialchars($p['marca']) ?></b><br><small class="text-muted"><?= htmlspecialchars($p['nombre']) ?></small></td>
                <td><span class="badge bg-light text-dark border rounded-pill"><?= htmlspecialchars($p['categoria']) ?></span></td>
                <td class="text-center"><?= htmlspecialchars($p['peso']) ?></td>
                <td class="text-end text-muted">$<?= number_format($p['precio_compra'],0,',','.') ?></td>
                <td class="text-end fw-bold text-success">$<?= number_format($p['precio_venta'],0,',','.') ?></td>
                <td class="text-center fw-bold <?= $p['stock'] == 0 ? 'text-danger' : ($p['stock'] < $p['stock_min'] ? 'text-warning' : 'text-success') ?>"><?= $p['stock'] ?><small class="text-muted"> /<?= $p['stock_min'] ?></small></td>
                <td class="text-center">
                  <?php if($p['stock']==0): ?><span class="badge bg-danger-subtle text-danger rounded-pill px-2">Sin Stock</span>
                  <?php elseif($p['stock']<$p['stock_min']): ?><span class="badge bg-warning-subtle text-warning rounded-pill px-2">Stock Bajo</span>
                  <?php else: ?><span class="badge bg-success-subtle text-success rounded-pill px-2">Disponible</span><?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <a href="Controladores/ReporteControlador.php?tipo=inventario" class="btn btn-success rounded-pill px-4"><i class="bi bi-download me-1"></i> Exportar CSV</a>
        <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Alertas de stock -->
<div class="modal fade" id="modalAlertas" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-danger text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i> Productos con Stock Bajo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <?php if(empty($alertasDetalle)): ?>
          <div class="p-4 text-center text-success"><i class="bi bi-check-circle-fill fs-2"></i><p class="mt-2">¡Todos los productos tienen stock suficiente!</p></div>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Producto</th><th class="text-center">Stock Actual</th><th class="text-center">Stock Mínimo</th><th class="text-center">Déficit</th><th class="text-center">Estado</th></tr></thead>
            <tbody>
              <?php foreach($alertasDetalle as $a): ?>
              <tr>
                <td><b><?= htmlspecialchars($a['marca']) ?></b> – <?= htmlspecialchars($a['nombre']) ?><br><small class="text-muted"><?= $a['codigo'] ?></small></td>
                <td class="text-center fw-bold <?= $a['stock']==0?'text-danger':'text-warning' ?>"><?= $a['stock'] ?></td>
                <td class="text-center"><?= $a['stock_min'] ?></td>
                <td class="text-center text-danger fw-bold"><?= max(0,$a['stock_min']-$a['stock']) ?> uds.</td>
                <td class="text-center"><?= $a['stock']==0 ? '<span class="badge bg-danger rounded-pill">Sin Stock</span>' : '<span class="badge bg-warning text-dark rounded-pill">Bajo</span>' ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button></div>
    </div>
  </div>
</div>

<!-- Modal: Ventas del día -->
<div class="modal fade" id="modalVentas" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-cart-check me-2"></i> Ventas de Hoy — Total: $<?= number_format($ventasHoy,0,',','.') ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Código</th><th>Fecha</th><th>Cliente</th><th class="text-end">Total</th><th class="text-center">Método</th><th class="text-center">Estado</th></tr></thead>
            <tbody>
              <?php 
              $ventasHoyLista = array_filter($todasVentas, fn($v)=> date('Y-m-d', strtotime($v['fecha'])) === date('Y-m-d'));
              if(empty($ventasHoyLista)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No hay ventas registradas hoy.</td></tr>
              <?php else: foreach($ventasHoyLista as $v): ?>
              <tr>
                <td class="fw-bold"><?= htmlspecialchars($v['codigo_venta']) ?></td>
                <td class="text-muted small"><?= date('h:i A', strtotime($v['fecha'])) ?></td>
                <td><?= htmlspecialchars($v['cliente_nombre']) ?></td>
                <td class="text-end fw-bold text-success">$<?= number_format($v['total'],0,',','.') ?></td>
                <td class="text-center"><span class="badge bg-light text-dark border rounded-pill"><?= htmlspecialchars($v['metodo_pago']) ?></span></td>
                <td class="text-center"><span class="badge bg-success-subtle text-success rounded-pill px-2"><?= htmlspecialchars($v['estado']) ?></span></td>
              </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <a href="Controladores/ReporteControlador.php?tipo=ventas" class="btn btn-success rounded-pill px-4"><i class="bi bi-download me-1"></i> Exportar CSV</a>
        <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Clientes -->
<div class="modal fade" id="modalClientes" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-people me-2"></i> Directorio de Clientes</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Código</th><th>Nombre</th><th>Tipo</th><th>Contacto</th><th>Teléfono</th><th>Correo</th><th class="text-center">Estado</th></tr></thead>
            <tbody>
              <?php foreach($todosClientes as $c): ?>
              <tr>
                <td class="text-muted"><?= htmlspecialchars($c['codigo']) ?></td>
                <td class="fw-bold"><?= htmlspecialchars($c['nombre']) ?></td>
                <td><span class="badge bg-light text-dark border rounded-pill"><?= htmlspecialchars($c['tipo']) ?></span></td>
                <td><?= htmlspecialchars($c['contacto']) ?></td>
                <td><?= htmlspecialchars($c['telefono']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td class="text-center"><?= $c['estado']==='Activo' ? '<span class="badge bg-success-subtle text-success rounded-pill px-2">Activo</span>' : '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-2">Inactivo</span>' ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button></div>
    </div>
  </div>
</div>

<!-- Modal: Nueva Venta -->
<div class="modal fade" id="modalNuevaVenta" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-cart-plus me-2 text-warning"></i> Registrar Nueva Venta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div id="ventaAlert" class="alert d-none"></div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
            <select id="vCliente" class="form-select" required>
              <option value="">Seleccionar cliente...</option>
              <?php foreach($todosClientes as $c): if($c['estado']==='Activo'): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
              <?php endif; endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Método de Pago <span class="text-danger">*</span></label>
            <select id="vMetodo" class="form-select">
              <option value="Efectivo">Efectivo</option>
              <option value="Transferencia">Transferencia Bancaria</option>
              <option value="Tarjeta Débito">Tarjeta Débito/Crédito</option>
              <option value="Crédito">Crédito Interno</option>
            </select>
          </div>
        </div>
        <hr class="my-3">
        <h6 class="fw-bold mb-3">Productos a vender</h6>
        <div id="itemsVenta"></div>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill mt-2" onclick="agregarItemVenta()">
          <i class="bi bi-plus-circle me-1"></i> Agregar producto
        </button>
        <hr class="my-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Total: <span id="totalVenta" class="text-success">$0</span></h5>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4" onclick="guardarVenta()">
          <i class="bi bi-check-circle me-1"></i> Registrar Venta
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Agregar Insumo -->
<div class="modal fade" id="modalAgregarInsumo" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2 text-warning"></i> Registrar Nuevo Insumo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formInsumo">
        <div class="modal-body p-4">
          <div id="insumoAlert" class="alert d-none"></div>
          <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-bold">Código SKU *</label><input type="text" class="form-control" name="codigo" placeholder="PUR-XXX-00" required></div>
            <div class="col-md-4"><label class="form-label fw-bold">Marca *</label><input type="text" class="form-control" name="marca" placeholder="Dog Chow, Pro Plan..." required></div>
            <div class="col-md-4"><label class="form-label fw-bold">Peso *</label><input type="text" class="form-control" name="peso" placeholder="15 Kg" required></div>
            <div class="col-md-8"><label class="form-label fw-bold">Nombre / Descripción *</label><input type="text" class="form-control" name="nombre" placeholder="Adultos Razas Medianas" required></div>
            <div class="col-md-4"><label class="form-label fw-bold">Categoría *</label><input type="text" class="form-control" name="categoria" placeholder="Adulto, Cachorro..." required></div>
            <div class="col-md-3"><label class="form-label fw-bold">Stock Inicial *</label><input type="number" class="form-control" name="stock" min="0" value="0" required></div>
            <div class="col-md-3"><label class="form-label fw-bold">Stock Mínimo *</label><input type="number" class="form-control" name="stock_min" min="1" value="5" required></div>
            <div class="col-md-3"><label class="form-label fw-bold">Precio Costo *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" class="form-control" name="precio_compra" min="0" step="100" required></div></div>
            <div class="col-md-3"><label class="form-label fw-bold">Precio Venta *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" class="form-control" name="precio_venta" min="0" step="100" required></div></div>
          </div>
        </div>
        <div class="modal-footer bg-light rounded-bottom-4">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4" onclick="guardarInsumo()"><i class="bi bi-save me-1"></i> Guardar Insumo</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Reporte -->
<div class="modal fade" id="modalReporte" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-bar-graph me-2 text-warning"></i> Generar Reporte</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p class="text-muted mb-4">Selecciona el tipo de reporte a exportar en formato CSV (compatible con Excel):</p>
        <div class="d-grid gap-3">
          <a href="Controladores/ReporteControlador.php?tipo=inventario" class="btn btn-outline-success btn-lg rounded-pill">
            <i class="bi bi-boxes me-2"></i> Exportar Inventario Completo (CSV)
          </a>
          <a href="Controladores/ReporteControlador.php?tipo=ventas" class="btn btn-outline-primary btn-lg rounded-pill">
            <i class="bi bi-receipt me-2"></i> Exportar Historial de Ventas (CSV)
          </a>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button></div>
    </div>
  </div>
</div>

<!-- Modal: Registrar Cliente -->
<div class="modal fade" id="modalRegistrarCliente" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2 text-warning"></i> Registrar Nuevo Cliente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formCliente">
        <div class="modal-body p-4">
          <div id="clienteAlert" class="alert d-none"></div>
          <div class="mb-3"><label class="form-label fw-bold">Nombre / Razón Social *</label><input type="text" class="form-control" name="nombre" placeholder="Veterinaria El Ovejero" required></div>
          <div class="mb-3"><label class="form-label fw-bold">Tipo de Cliente *</label>
            <select class="form-select" name="tipo" required>
              <option value="Minorista">Minorista</option>
              <option value="Mayorista">Mayorista</option>
              <option value="Distribuidor">Distribuidor</option>
            </select>
          </div>
          <div class="mb-3"><label class="form-label fw-bold">Persona de Contacto *</label><input type="text" class="form-control" name="contacto" placeholder="Dr. Juan Gómez" required></div>
          <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label fw-bold">Teléfono *</label><input type="text" class="form-control" name="telefono" placeholder="+57 300 000 0000" required></div>
            <div class="col-md-6 mb-3"><label class="form-label fw-bold">Correo *</label><input type="email" class="form-control" name="email" placeholder="contacto@vet.com" required></div>
          </div>
        </div>
        <div class="modal-footer bg-light rounded-bottom-4">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4" onclick="guardarCliente()"><i class="bi bi-save me-1"></i> Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Toast de notificaciones -->
<div class="toast-container">
  <div id="toastMsg" class="toast align-items-center text-white border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body fw-semibold" id="toastText"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary border-opacity-20">
  <div class="container-fluid px-5">
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start"><p class="mb-0">&copy; 2026 <span class="text-white fw-bold">TiendaInsumo</span>. Todos los derechos reservados.</p></div>
      <div class="col-md-6 text-center text-md-end mt-2 mt-md-0"><span class="badge bg-secondary-subtle text-dark border border-secondary-subtle">v2.0.0 — PHP + MySQL</span></div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ---- PRODUCTOS disponibles para venta ----
const productosDisponibles = <?= json_encode(array_map(fn($p)=>[
  'id'=>$p['id'],
  'nombre'=>$p['marca'].' – '.$p['nombre'],
  'precio'=>(float)$p['precio_venta'],
  'stock'=>(int)$p['stock']
], $todosProductos)) ?>;

// ---- Items de venta ----
let itemCount = 0;
function agregarItemVenta() {
  itemCount++;
  const id = 'item_' + itemCount;
  const opts = productosDisponibles.map(p=>
    `<option value="${p.id}" data-precio="${p.precio}" data-stock="${p.stock}">${p.nombre} (Stock: ${p.stock}) — $${p.precio.toLocaleString('es-CO')}</option>`
  ).join('');
  const html = `
    <div class="row g-2 mb-2 align-items-end" id="${id}">
      <div class="col-md-6"><select class="form-select form-select-sm producto-sel" onchange="calcularTotal()">${opts}</select></div>
      <div class="col-md-3"><input type="number" class="form-control form-control-sm cantidad-inp" value="1" min="1" onchange="calcularTotal()" placeholder="Cant."></div>
      <div class="col-md-2"><span class="badge bg-light text-dark border w-100 py-2 subtotal-lbl">$0</span></div>
      <div class="col-md-1"><button class="btn btn-outline-danger btn-sm rounded-circle" onclick="document.getElementById('${id}').remove();calcularTotal()"><i class="bi bi-x"></i></button></div>
    </div>`;
  document.getElementById('itemsVenta').insertAdjacentHTML('beforeend', html);
  calcularTotal();
}
function calcularTotal() {
  let total = 0;
  document.querySelectorAll('#itemsVenta .row').forEach(row => {
    const sel = row.querySelector('.producto-sel');
    const qty = parseInt(row.querySelector('.cantidad-inp').value) || 0;
    const precio = parseFloat(sel.selectedOptions[0]?.dataset.precio || 0);
    const sub = precio * qty;
    total += sub;
    row.querySelector('.subtotal-lbl').textContent = '$' + sub.toLocaleString('es-CO');
  });
  document.getElementById('totalVenta').textContent = '$' + total.toLocaleString('es-CO');
}
function guardarVenta() {
  const clienteId = document.getElementById('vCliente').value;
  const metodo = document.getElementById('vMetodo').value;
  if (!clienteId) { showAlert('ventaAlert','Por favor selecciona un cliente.','warning'); return; }
  const rows = document.querySelectorAll('#itemsVenta .row');
  if (!rows.length) { showAlert('ventaAlert','Agrega al menos un producto.','warning'); return; }
  const items = [];
  let valid = true;
  rows.forEach(row => {
    const sel = row.querySelector('.producto-sel');
    const qty = parseInt(row.querySelector('.cantidad-inp').value) || 0;
    const stock = parseInt(sel.selectedOptions[0]?.dataset.stock || 0);
    const precio = parseFloat(sel.selectedOptions[0]?.dataset.precio || 0);
    if (qty <= 0) { valid = false; }
    if (qty > stock) { showAlert('ventaAlert',`Stock insuficiente: solo hay ${stock} unidades disponibles.`,'danger'); valid = false; }
    items.push({ producto_id: parseInt(sel.value), cantidad: qty, precio_unitario: precio });
  });
  if (!valid) return;
  fetch('Controladores/VentaControlador.php?action=crear', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ cliente_id: parseInt(clienteId), metodo_pago: metodo, items })
  })
  .then(r=>r.json())
  .then(data => {
    if (data.ok) {
      bootstrap.Modal.getInstance(document.getElementById('modalNuevaVenta')).hide();
      showToast('✅ Venta registrada: ' + data.codigo + ' — Total: $' + parseFloat(data.total).toLocaleString('es-CO'),'bg-success');
      setTimeout(()=>location.reload(), 2000);
    } else {
      showAlert('ventaAlert', data.error || 'Error al registrar la venta.','danger');
    }
  })
  .catch(()=>showAlert('ventaAlert','Error de conexión con el servidor.','danger'));
}

// ---- Agregar Insumo ----
function guardarInsumo() {
  const form = document.getElementById('formInsumo');
  if (!form.checkValidity()) { form.reportValidity(); return; }
  const fd = new FormData(form);
  fd.append('action','crear');
  fetch('Controladores/ProductoControlador.php', { method:'POST', body: fd })
  .then(r=>r.json())
  .then(data => {
    if (data.ok) {
      bootstrap.Modal.getInstance(document.getElementById('modalAgregarInsumo')).hide();
      showToast('✅ Insumo registrado correctamente.','bg-success');
      setTimeout(()=>location.reload(), 1500);
    } else {
      showAlert('insumoAlert', data.error || 'Error al guardar el insumo.','danger');
    }
  })
  .catch(()=>showAlert('insumoAlert','Error de conexión.','danger'));
}

// ---- Registrar Cliente ----
function guardarCliente() {
  const form = document.getElementById('formCliente');
  if (!form.checkValidity()) { form.reportValidity(); return; }
  const fd = new FormData(form);
  fd.append('action','crear');
  fetch('Controladores/ClienteControlador.php', { method:'POST', body: fd })
  .then(r=>r.json())
  .then(data => {
    if (data.ok) {
      bootstrap.Modal.getInstance(document.getElementById('modalRegistrarCliente')).hide();
      showToast('✅ Cliente registrado correctamente.','bg-success');
      setTimeout(()=>location.reload(), 1500);
    } else {
      showAlert('clienteAlert', data.error || 'Error al registrar el cliente.','danger');
    }
  })
  .catch(()=>showAlert('clienteAlert','Error de conexión.','danger'));
}

// ---- Reporte ----
function abrirReporte() {
  new bootstrap.Modal(document.getElementById('modalReporte')).show();
}

// ---- Helpers ----
function showAlert(id, msg, type='danger') {
  const el = document.getElementById(id);
  el.className = `alert alert-${type}`;
  el.textContent = msg;
  el.classList.remove('d-none');
}
function showToast(msg, bgClass='bg-success') {
  const el = document.getElementById('toastMsg');
  document.getElementById('toastText').textContent = msg;
  el.className = `toast align-items-center text-white border-0 ${bgClass}`;
  new bootstrap.Toast(el, { delay: 4000 }).show();
}

// Inicializar tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>new bootstrap.Tooltip(el));
</script>
</body>
</html>
<?php endif; ?>
