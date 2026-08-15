<?php
session_start();
require_once __DIR__ . '/../Modelos/ContabilidadCompleta.php';
$cont     = new ContabilidadCompleta();
$resumen  = $cont->resumen();
$balance  = $cont->balance();
$cuentas  = $cont->cuentas();
$clientes = $cont->clientes();
$provs    = $cont->proveedores();
$flujo    = $cont->flujoMensual();
$mesesL   = json_encode(array_column($flujo,'mes_label'));
$ingresosM= json_encode(array_column($flujo,'ingresos'));
$egresosM = json_encode(array_column($flujo,'egresos'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Contabilidad – TiendaInsumo</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="../public/css/style.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
body{background:#f8fafc;font-family:'Inter',sans-serif}
.kpi{background:#fff;border-radius:14px;padding:20px 22px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.05);transition:transform .2s,box-shadow .2s}
.kpi:hover{transform:translateY(-3px);box-shadow:0 6px 18px rgba(0,0,0,.08)}
.kpi-val{font-size:1.6rem;font-weight:800;letter-spacing:-.5px}
.kpi-ico{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem}
.tab-btn{border:none;background:none;padding:10px 18px;font-size:.875rem;font-weight:500;color:#6b7280;border-bottom:2px solid transparent;cursor:pointer;transition:all .2s}
.tab-btn.active{color:#1e7a38;border-bottom-color:#1e7a38;font-weight:700}
.tab-content{display:none}.tab-content.active{display:block}
.card-sec{background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.05);padding:24px;margin-bottom:20px}
.badge-est-Pendiente{background:#fef9c3;color:#854d0e}
.badge-est-Parcial{background:#dbeafe;color:#1d4ed8}
.badge-est-Pagada{background:#dcfce7;color:#15803d}
.badge-est-Vencida{background:#fee2e2;color:#b91c1c}
.badge-est-Anulado,.badge-est-Anulada{background:#f1f5f9;color:#64748b}
.badge-est-Completado{background:#dcfce7;color:#15803d}
.badge-est-Registrado{background:#dcfce7;color:#15803d}
.badge-tipo{font-size:.72rem;padding:3px 9px;border-radius:20px;font-weight:600}
.search-bar{border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 14px;font-size:.875rem;outline:none;transition:border-color .2s}
.search-bar:focus{border-color:#1e7a38;box-shadow:0 0 0 3px rgba(30,122,56,.1)}
.btn-acc{padding:6px 14px;border-radius:8px;font-size:.78rem;font-weight:600;border:none;cursor:pointer;transition:background .2s}
.btn-acc-green{background:#dcfce7;color:#15803d}.btn-acc-green:hover{background:#bbf7d0}
.btn-acc-red{background:#fee2e2;color:#b91c1c}.btn-acc-red:hover{background:#fecaca}
.btn-acc-blue{background:#dbeafe;color:#1d4ed8}.btn-acc-blue:hover{background:#bfdbfe}
.balance-box{border-radius:14px;padding:20px 24px;text-align:center}
.toast-container{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999}
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
        <li class="nav-item"><a class="nav-link nav-link-custom active" href="contabilidad_full.php"><i class="bi bi-calculator me-1"></i> Contabilidad</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="estadisticas.php"><i class="bi bi-bar-chart-line me-1"></i> Estadísticas</a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- HEADER -->
<header class="bg-white border-bottom py-3 mb-4">
  <div class="container-fluid px-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <ol class="breadcrumb small mb-1"><li class="breadcrumb-item"><a href="../dashboard.php">Inicio</a></li><li class="breadcrumb-item active">Contabilidad</li></ol>
      <h3 class="fw-bold mb-0"><i class="bi bi-calculator-fill text-success me-2"></i>Módulo de Contabilidad</h3>
      <p class="text-muted small mb-0">Ingresos · Egresos · CxC · CxP · Gastos · Balance</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalMovimiento"><i class="bi bi-plus-circle me-1"></i> Nuevo Movimiento</button>
      <button class="btn btn-warning text-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalGasto"><i class="bi bi-receipt me-1"></i> Registrar Gasto</button>
    </div>
  </div>
</header>
<main class="container-fluid px-5 pb-5">
<!-- KPIs -->
<section class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="kpi"><div class="d-flex justify-content-between"><div><p class="text-muted small fw-semibold mb-1 text-uppercase">Ingresos Totales</p><div class="kpi-val text-success">$<?=number_format($resumen['ingresos_totales'],0,',','.')?></div><small class="text-muted">Ventas completadas</small></div><div class="kpi-ico bg-success bg-opacity-10 text-success"><i class="bi bi-graph-up-arrow"></i></div></div></div></div>
  <div class="col-6 col-lg-3"><div class="kpi"><div class="d-flex justify-content-between"><div><p class="text-muted small fw-semibold mb-1 text-uppercase">Gastos Totales</p><div class="kpi-val text-danger">$<?=number_format($resumen['gastos_totales'],0,',','.')?></div><small class="text-muted">Egresos registrados</small></div><div class="kpi-ico bg-danger bg-opacity-10 text-danger"><i class="bi bi-graph-down-arrow"></i></div></div></div></div>
  <div class="col-6 col-lg-3"><div class="kpi"><div class="d-flex justify-content-between"><div><p class="text-muted small fw-semibold mb-1 text-uppercase">Utilidad Bruta</p><div class="kpi-val <?=$resumen['utilidad_bruta']>=0?'text-primary':'text-danger'?>">$<?=number_format(abs($resumen['utilidad_bruta']),0,',','.')?></div><small class="text-muted"><?=$resumen['utilidad_bruta']>=0?'Positiva':'Negativa'?></small></div><div class="kpi-ico bg-primary bg-opacity-10 text-primary"><i class="bi bi-cash-stack"></i></div></div></div></div>
  <div class="col-6 col-lg-3"><div class="kpi"><div class="d-flex justify-content-between"><div><p class="text-muted small fw-semibold mb-1 text-uppercase">CxC Pendiente</p><div class="kpi-val text-warning">$<?=number_format($resumen['cxc_pendiente'],0,',','.')?></div><small class="text-muted">Por cobrar</small></div><div class="kpi-ico bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div></div></div></div>
</section>
<!-- BALANCE + GRÁFICA -->
<section class="row g-4 mb-4">
  <div class="col-lg-4">
    <div class="card-sec h-100">
      <h6 class="fw-bold mb-4"><i class="bi bi-bar-chart-line text-success me-2"></i>Balance General</h6>
      <div class="balance-box bg-success bg-opacity-10 mb-3"><p class="text-muted small mb-1">ACTIVOS TOTALES</p><h4 class="fw-bold text-success mb-0">$<?=number_format($balance['activos'],0,',','.')?></h4></div>
      <div class="balance-box bg-danger bg-opacity-10 mb-3"><p class="text-muted small mb-1">PASIVOS TOTALES</p><h4 class="fw-bold text-danger mb-0">$<?=number_format($balance['pasivos'],0,',','.')?></h4></div>
      <div class="balance-box <?=$balance['patrimonio']>=0?'bg-primary':'bg-warning'?> bg-opacity-10"><p class="text-muted small mb-1">PATRIMONIO NETO</p><h4 class="fw-bold <?=$balance['patrimonio']>=0?'text-primary':'text-warning'?> mb-0">$<?=number_format(abs($balance['patrimonio']),0,',','.')?></h4></div>
      <hr class="my-3">
      <div class="d-flex justify-content-between text-muted small"><span>CxC pendiente:</span><span class="fw-bold text-warning">$<?=number_format($balance['cxc'],0,',','.')?></span></div>
      <div class="d-flex justify-content-between text-muted small mt-1"><span>CxP pendiente:</span><span class="fw-bold text-danger">$<?=number_format($balance['cxp'],0,',','.')?></span></div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card-sec h-100">
      <h6 class="fw-bold mb-1"><i class="bi bi-activity text-primary me-2"></i>Flujo de Caja — Últimos 6 Meses</h6>
      <p class="text-muted small mb-4">Ingresos vs Egresos mensuales</p>
      <canvas id="chartFlujo" height="90"></canvas>
    </div>
  </div>
</section>
<!-- TABS -->
<div class="card-sec">
  <div class="d-flex border-bottom mb-4 gap-1 flex-wrap">
    <button class="tab-btn active" onclick="showTab('movimientos')"><i class="bi bi-journal-text me-1"></i>Libro Diario</button>
    <button class="tab-btn" onclick="showTab('cxc')"><i class="bi bi-arrow-down-circle me-1"></i>Cuentas x Cobrar</button>
    <button class="tab-btn" onclick="showTab('cxp')"><i class="bi bi-arrow-up-circle me-1"></i>Cuentas x Pagar</button>
    <button class="tab-btn" onclick="showTab('gastos')"><i class="bi bi-wallet2 me-1"></i>Gastos</button>
  </div>

  <!-- TAB: MOVIMIENTOS -->
  <div id="tab-movimientos" class="tab-content active">
    <div class="row g-2 mb-3">
      <div class="col-md-3"><input type="text" id="buscarMov" class="form-control search-bar w-100" placeholder="🔍 Buscar concepto..."></div>
      <div class="col-md-2"><select id="filtroTipoMov" class="form-select form-select-sm"><option value="">Todos los tipos</option><option>Ingreso</option><option>Egreso</option><option>Venta</option><option>Compra</option><option>CxC</option><option>CxP</option><option>Gasto</option><option>Ajuste</option></select></div>
      <div class="col-md-2"><input type="date" id="desdeM" class="form-control form-control-sm"></div>
      <div class="col-md-2"><input type="date" id="hastaM" class="form-control form-control-sm"></div>
      <div class="col-md-3"><button onclick="cargarMovimientos()" class="btn btn-sm btn-success rounded-pill px-3 me-2"><i class="bi bi-funnel"></i> Filtrar</button><button onclick="limpiarFiltros()" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-x"></i></button></div>
    </div>
    <div class="table-responsive" id="tablaMovContent"><div class="text-center py-4"><div class="spinner-border text-success"></div></div></div>
  </div>

  <!-- TAB: CxC -->
  <div id="tab-cxc" class="tab-content">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
      <select id="filtroCxC" class="form-select form-select-sm" style="max-width:180px" onchange="cargarCxC()"><option value="">Todos</option><option>Pendiente</option><option>Parcial</option><option>Pagada</option><option>Vencida</option></select>
      <button class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCxC"><i class="bi bi-plus me-1"></i>Nueva CxC</button>
    </div>
    <div class="table-responsive" id="tablaCxCContent"><div class="text-center py-4"><div class="spinner-border text-success"></div></div></div>
  </div>

  <!-- TAB: CxP -->
  <div id="tab-cxp" class="tab-content">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
      <select id="filtroCxP" class="form-select form-select-sm" style="max-width:180px" onchange="cargarCxP()"><option value="">Todos</option><option>Pendiente</option><option>Parcial</option><option>Pagada</option><option>Vencida</option></select>
      <button class="btn btn-sm btn-warning text-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCxP"><i class="bi bi-plus me-1"></i>Nueva CxP</button>
    </div>
    <div class="table-responsive" id="tablaCxPContent"><div class="text-center py-4"><div class="spinner-border text-success"></div></div></div>
  </div>

  <!-- TAB: GASTOS -->
  <div id="tab-gastos" class="tab-content">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
      <select id="filtroGasto" class="form-select form-select-sm" style="max-width:200px" onchange="cargarGastos()"><option value="">Todas las categorías</option><?php foreach($cont->categoriasGastos() as $cat): ?><option><?=htmlspecialchars($cat)?></option><?php endforeach; ?></select>
      <button class="btn btn-sm btn-warning text-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalGasto"><i class="bi bi-plus me-1"></i>Nuevo Gasto</button>
    </div>
    <div class="table-responsive" id="tablaGastosContent"><div class="text-center py-4"><div class="spinner-border text-success"></div></div></div>
  </div>
</div>
</main>

<!-- MODAL: Nuevo Movimiento -->
<div class="modal fade" id="modalMovimiento" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4"><h5 class="modal-title fw-bold"><i class="bi bi-journal-plus me-2 text-warning"></i>Registrar Movimiento</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div id="alertMov" class="alert d-none"></div>
        <div class="row g-3">
          <div class="col-6"><label class="form-label fw-bold small">Fecha *</label><input type="date" id="mFecha" class="form-control" value="<?=date('Y-m-d')?>"></div>
          <div class="col-6"><label class="form-label fw-bold small">Tipo *</label><select id="mTipo" class="form-select"><option>Ingreso</option><option>Egreso</option><option>Venta</option><option>Compra</option><option>CxC</option><option>CxP</option><option>Gasto</option><option>Ajuste</option></select></div>
          <div class="col-12"><label class="form-label fw-bold small">Concepto *</label><input type="text" id="mConcepto" class="form-control" placeholder="Descripción del movimiento"></div>
          <div class="col-6"><label class="form-label fw-bold small">Monto *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" id="mMonto" class="form-control" min="0" step="100"></div></div>
          <div class="col-6"><label class="form-label fw-bold small">Cuenta Contable</label><select id="mCuenta" class="form-select"><option value="">-- Seleccionar --</option><?php foreach($cuentas as $c): ?><option value="<?=$c['id']?>"><?=htmlspecialchars($c['codigo'].' - '.$c['nombre'])?></option><?php endforeach; ?></select></div>
          <div class="col-6"><label class="form-label fw-bold small">Referencia</label><input type="text" id="mRef" class="form-control" placeholder="Nº factura, venta..."></div>
          <div class="col-6"><label class="form-label fw-bold small">Cliente</label><select id="mCliente" class="form-select"><option value="">-- Ninguno --</option><?php foreach($clientes as $c): ?><option value="<?=$c['id']?>"><?=htmlspecialchars($c['nombre'])?></option><?php endforeach; ?></select></div>
          <div class="col-12"><label class="form-label fw-bold small">Notas</label><textarea id="mNotas" class="form-control" rows="2"></textarea></div>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4"><button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-success rounded-pill px-4 fw-bold" onclick="guardarMovimiento()"><i class="bi bi-save me-1"></i>Guardar</button></div>
    </div>
  </div>
</div>
<!-- MODAL: Gasto -->
<div class="modal fade" id="modalGasto" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-warning text-dark rounded-top-4"><h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Registrar Gasto</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div id="alertGasto" class="alert d-none"></div>
        <div class="row g-3">
          <div class="col-6"><label class="form-label fw-bold small">Fecha *</label><input type="date" id="gFecha" class="form-control" value="<?=date('Y-m-d')?>"></div>
          <div class="col-6"><label class="form-label fw-bold small">Categoría *</label><input type="text" id="gCategoria" class="form-control" placeholder="Transporte, Servicios..."></div>
          <div class="col-12"><label class="form-label fw-bold small">Descripción *</label><input type="text" id="gDesc" class="form-control" placeholder="Detalle del gasto"></div>
          <div class="col-6"><label class="form-label fw-bold small">Monto *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" id="gMonto" class="form-control" min="0" step="100"></div></div>
          <div class="col-6"><label class="form-label fw-bold small">Método Pago</label><select id="gMetodo" class="form-select"><option>Efectivo</option><option>Transferencia</option><option>Tarjeta Débito</option><option>Crédito</option></select></div>
          <div class="col-12"><label class="form-label fw-bold small">N° Comprobante</label><input type="text" id="gComprobante" class="form-control" placeholder="Opcional"></div>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4"><button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-warning text-dark rounded-pill px-4 fw-bold" onclick="guardarGasto()"><i class="bi bi-save me-1"></i>Guardar</button></div>
    </div>
  </div>
</div>

<!-- MODAL: CxC -->
<div class="modal fade" id="modalCxC" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4"><h5 class="modal-title fw-bold"><i class="bi bi-arrow-down-circle me-2 text-warning"></i>Nueva Cuenta por Cobrar</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div id="alertCxC" class="alert d-none"></div>
        <div class="row g-3">
          <div class="col-12"><label class="form-label fw-bold small">Cliente *</label><select id="cxcCliente" class="form-select"><option value="">-- Seleccionar --</option><?php foreach($clientes as $c): ?><option value="<?=$c['id']?>"><?=htmlspecialchars($c['nombre'])?></option><?php endforeach; ?></select></div>
          <div class="col-12"><label class="form-label fw-bold small">Concepto *</label><input type="text" id="cxcConcepto" class="form-control" placeholder="Por qué se debe el dinero"></div>
          <div class="col-6"><label class="form-label fw-bold small">Monto Total *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" id="cxcMonto" class="form-control" min="0" step="100"></div></div>
          <div class="col-6"><label class="form-label fw-bold small">Fecha Emisión *</label><input type="date" id="cxcEmision" class="form-control" value="<?=date('Y-m-d')?>"></div>
          <div class="col-6"><label class="form-label fw-bold small">Fecha Vence</label><input type="date" id="cxcVence" class="form-control"></div>
          <div class="col-12"><label class="form-label fw-bold small">Notas</label><textarea id="cxcNotas" class="form-control" rows="2"></textarea></div>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4"><button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-success rounded-pill px-4 fw-bold" onclick="guardarCxC()"><i class="bi bi-save me-1"></i>Guardar</button></div>
    </div>
  </div>
</div>
<!-- MODAL: CxP -->
<div class="modal fade" id="modalCxP" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white rounded-top-4"><h5 class="modal-title fw-bold"><i class="bi bi-arrow-up-circle me-2 text-warning"></i>Nueva Cuenta por Pagar</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div id="alertCxP" class="alert d-none"></div>
        <div class="row g-3">
          <div class="col-12"><label class="form-label fw-bold small">Proveedor</label><select id="cxpProv" class="form-select"><option value="">-- Sin proveedor --</option><?php foreach($provs as $p): ?><option value="<?=$p['id']?>"><?=htmlspecialchars($p['nombre'])?></option><?php endforeach; ?></select></div>
          <div class="col-12"><label class="form-label fw-bold small">Concepto *</label><input type="text" id="cxpConcepto" class="form-control" placeholder="Compra de mercancía, servicios..."></div>
          <div class="col-6"><label class="form-label fw-bold small">Monto Total *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" id="cxpMonto" class="form-control" min="0" step="100"></div></div>
          <div class="col-6"><label class="form-label fw-bold small">Fecha Emisión *</label><input type="date" id="cxpEmision" class="form-control" value="<?=date('Y-m-d')?>"></div>
          <div class="col-6"><label class="form-label fw-bold small">Fecha Vence</label><input type="date" id="cxpVence" class="form-control"></div>
          <div class="col-12"><label class="form-label fw-bold small">Notas</label><textarea id="cxpNotas" class="form-control" rows="2"></textarea></div>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4"><button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-warning text-dark rounded-pill px-4 fw-bold" onclick="guardarCxP()"><i class="bi bi-save me-1"></i>Guardar</button></div>
    </div>
  </div>
</div>

<!-- MODAL: Registrar Pago -->
<div class="modal fade" id="modalPago" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-success text-white rounded-top-4"><h5 class="modal-title fw-bold"><i class="bi bi-cash me-2"></i>Registrar Pago</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <input type="hidden" id="pagoId"><input type="hidden" id="pagoTipo">
        <label class="form-label fw-bold small">Monto a Pagar *</label>
        <div class="input-group"><span class="input-group-text">$</span><input type="number" id="pagoMonto" class="form-control" min="1" step="100"></div>
        <small class="text-muted">Saldo pendiente: <strong id="pagoSaldo"></strong></small>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4"><button class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-success rounded-pill px-4 fw-bold" onclick="confirmarPago()"><i class="bi bi-check me-1"></i>Confirmar</button></div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast-container"><div id="toastMsg" class="toast align-items-center text-white border-0" role="alert"><div class="d-flex"><div class="toast-body fw-semibold" id="toastText"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div></div>

<footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary border-opacity-20">
  <div class="container-fluid px-5 text-center"><p class="mb-0">&copy; 2026 <span class="text-white fw-bold">TiendaInsumo</span> — Módulo de Contabilidad</p></div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = '../Controladores/ContabilidadControlador.php';
const fmt = n => '$'+parseFloat(n||0).toLocaleString('es-CO',{minimumFractionDigits:0});

// Gráfica flujo
new Chart(document.getElementById('chartFlujo'),{
  type:'bar',
  data:{labels:<?=$mesesL?>,datasets:[
    {label:'Ingresos',data:<?=$ingresosM?>,backgroundColor:'rgba(34,197,94,.3)',borderColor:'#22c55e',borderWidth:2,borderRadius:6},
    {label:'Egresos', data:<?=$egresosM?>, backgroundColor:'rgba(239,68,68,.3)',borderColor:'#ef4444',borderWidth:2,borderRadius:6}
  ]},
  options:{responsive:true,plugins:{legend:{position:'top'}},scales:{y:{ticks:{callback:v=>fmt(v)}}}}
});

// Tabs
function showTab(name){
  document.querySelectorAll('.tab-btn').forEach((b,i)=>{b.classList.toggle('active',b.getAttribute('onclick').includes(name))});
  document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
  document.getElementById('tab-'+name).classList.add('active');
  if(name==='movimientos') cargarMovimientos();
  if(name==='cxc') cargarCxC();
  if(name==='cxp') cargarCxP();
  if(name==='gastos') cargarGastos();
}

// Toast
function toast(msg,bg='bg-success'){
  const el=document.getElementById('toastMsg');
  document.getElementById('toastText').textContent=msg;
  el.className='toast align-items-center text-white border-0 '+bg;
  new bootstrap.Toast(el,{delay:3500}).show();
}
function alert2(id,msg,tipo='danger'){
  const el=document.getElementById(id);
  el.className='alert alert-'+tipo;el.textContent=msg;el.classList.remove('d-none');
}
function postJSON(action,data,cb){
  fetch(API+'?action='+action,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data)})
  .then(r=>r.json()).then(cb).catch(()=>toast('Error de conexión','bg-danger'));
}

// ── MOVIMIENTOS ──────────────────────────────────────────────
function cargarMovimientos(){
  const body={tipo:document.getElementById('filtroTipoMov').value,buscar:document.getElementById('buscarMov').value,desde:document.getElementById('desdeM').value,hasta:document.getElementById('hastaM').value};
  document.getElementById('tablaMovContent').innerHTML='<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
  postJSON('movimientos',body,d=>{
    if(!d.ok){document.getElementById('tablaMovContent').innerHTML='<p class="text-danger p-3">'+d.error+'</p>';return;}
    const rows=d.data.map(m=>`<tr>
      <td class="text-muted small">${m.fecha}</td>
      <td><span class="badge badge-tipo badge-est-${m.tipo}">${m.tipo}</span></td>
      <td>${m.concepto}</td>
      <td class="text-muted small">${m.cuenta_nombre||'—'}</td>
      <td class="text-muted small">${m.referencia||'—'}</td>
      <td class="text-muted small">${m.cliente_nombre||'—'}</td>
      <td class="fw-bold ${m.tipo==='Ingreso'||m.tipo==='Venta'?'text-success':'text-danger'}">${fmt(m.monto)}</td>
      <td><span class="badge badge-tipo badge-est-${m.estado}">${m.estado}</span></td>
      <td><button class="btn-acc btn-acc-red" onclick="anular('movimientos_contables',${m.id})">Anular</button></td>
    </tr>`).join('');
    document.getElementById('tablaMovContent').innerHTML=`<table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Fecha</th><th>Tipo</th><th>Concepto</th><th>Cuenta</th><th>Referencia</th><th>Cliente</th><th>Monto</th><th>Estado</th><th></th></tr></thead><tbody>${rows||'<tr><td colspan="9" class="text-center text-muted py-4">Sin registros</td></tr>'}</tbody></table>`;
  });
}
function limpiarFiltros(){document.getElementById('filtroTipoMov').value='';document.getElementById('buscarMov').value='';document.getElementById('desdeM').value='';document.getElementById('hastaM').value='';cargarMovimientos();}
function guardarMovimiento(){
  const d={fecha:document.getElementById('mFecha').value,tipo:document.getElementById('mTipo').value,concepto:document.getElementById('mConcepto').value,monto:document.getElementById('mMonto').value,cuenta_id:document.getElementById('mCuenta').value,referencia:document.getElementById('mRef').value,cliente_id:document.getElementById('mCliente').value,notas:document.getElementById('mNotas').value};
  if(!d.fecha||!d.tipo||!d.concepto||!d.monto){alert2('alertMov','Complete los campos requeridos');return;}
  postJSON('registrar_movimiento',d,r=>{if(r.ok){bootstrap.Modal.getInstance(document.getElementById('modalMovimiento')).hide();toast('✅ Movimiento registrado');cargarMovimientos();}else alert2('alertMov',r.error||'Error');});
}
document.getElementById('modalMovimiento').addEventListener('show.bs.modal',()=>{document.getElementById('alertMov').className='alert d-none';});

// ── CxC ──────────────────────────────────────────────────────
function cargarCxC(){
  document.getElementById('tablaCxCContent').innerHTML='<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
  postJSON('cxc',{estado:document.getElementById('filtroCxC').value},d=>{
    const rows=d.data.map(c=>`<tr>
      <td class="fw-bold">${c.cliente_nombre}</td>
      <td>${c.concepto}</td>
      <td class="text-end fw-bold">${fmt(c.monto_total)}</td>
      <td class="text-end text-success fw-bold">${fmt(c.monto_pagado)}</td>
      <td class="text-end text-danger fw-bold">${fmt(c.monto_total-c.monto_pagado)}</td>
      <td>${c.fecha_emision}</td>
      <td class="${c.fecha_vence&&c.fecha_vence<'<?=date('Y-m-d')?>'?'text-danger':''}">${c.fecha_vence||'—'}</td>
      <td><span class="badge badge-tipo badge-est-${c.estado}">${c.estado}</span></td>
      <td class="d-flex gap-1">
        ${c.estado!='Pagada'&&c.estado!='Anulada'?`<button class="btn-acc btn-acc-green" onclick="abrirPago(${c.id},'cxc',${c.monto_total-c.monto_pagado})">Cobrar</button>`:''}
        <button class="btn-acc btn-acc-red" onclick="anular('cuentas_cobrar',${c.id})">Anular</button>
      </td>
    </tr>`).join('');
    document.getElementById('tablaCxCContent').innerHTML=`<table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Cliente</th><th>Concepto</th><th class="text-end">Total</th><th class="text-end">Pagado</th><th class="text-end">Saldo</th><th>Emisión</th><th>Vence</th><th>Estado</th><th></th></tr></thead><tbody>${rows||'<tr><td colspan="9" class="text-center text-muted py-4">Sin registros</td></tr>'}</tbody></table>`;
  });
}
function guardarCxC(){
  const d={cliente_id:document.getElementById('cxcCliente').value,concepto:document.getElementById('cxcConcepto').value,monto_total:document.getElementById('cxcMonto').value,fecha_emision:document.getElementById('cxcEmision').value,fecha_vence:document.getElementById('cxcVence').value,notas:document.getElementById('cxcNotas').value};
  if(!d.cliente_id||!d.concepto||!d.monto_total||!d.fecha_emision){alert2('alertCxC','Complete los campos requeridos');return;}
  postJSON('registrar_cxc',d,r=>{if(r.ok){bootstrap.Modal.getInstance(document.getElementById('modalCxC')).hide();toast('✅ CxC registrada');cargarCxC();}else alert2('alertCxC',r.error||'Error');});
}
document.getElementById('modalCxC').addEventListener('show.bs.modal',()=>{document.getElementById('alertCxC').className='alert d-none';});
</script>
