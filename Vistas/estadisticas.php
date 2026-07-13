<?php
session_start();
require_once __DIR__ . '/../Modelos/ContabilidadCompleta.php';
$cont  = new ContabilidadCompleta();
$stats = $cont->estadisticas();
$res   = $cont->resumen();

// JSON para gráficas
$diasL     = json_encode(array_column($stats['dias_semana'],  'dia'));
$diasV     = json_encode(array_column($stats['dias_semana'],  'total'));
$clientesL = json_encode(array_column($stats['top_clientes'], 'nombre'));
$clientesV = json_encode(array_column($stats['top_clientes'], 'total'));
$gastosL   = json_encode(array_column($stats['gastos_cat'],   'categoria'));
$gastosV   = json_encode(array_column($stats['gastos_cat'],   'total'));
$metodosL  = json_encode(array_column($stats['metodos'],      'metodo_pago'));
$metodosV  = json_encode(array_column($stats['metodos'],      'total'));

// Flujo 12 meses — merge ingresos y egresos por mes_key
$flujoMap = [];
foreach($stats['flujo12_ing'] as $r) $flujoMap[$r['mes_key']] = ['mes'=>$r['mes'],'ing'=>$r['ingresos'],'egr'=>0];
foreach($stats['flujo12_egr'] as $r) {
    if(isset($flujoMap[$r['mes_key']])) $flujoMap[$r['mes_key']]['egr']=$r['egresos'];
    else $flujoMap[$r['mes_key']] = ['mes'=>$r['mes'],'ing'=>0,'egr'=>$r['egresos']];
}
ksort($flujoMap);
$flujo12L  = json_encode(array_values(array_column(array_values($flujoMap),'mes')));
$flujo12I  = json_encode(array_values(array_column(array_values($flujoMap),'ing')));
$flujo12E  = json_encode(array_values(array_column(array_values($flujoMap),'egr')));

$ticket = $stats['ticket'];
$crec   = $stats['crecimiento'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Estadísticas – TiendaInsumo</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="../public/css/style.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
body{background:#f8fafc;font-family:'Inter',sans-serif}
.stat-card{background:#fff;border-radius:14px;padding:22px 24px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.05);height:100%}
.kpi{background:#fff;border-radius:14px;padding:18px 22px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.05);transition:transform .2s}
.kpi:hover{transform:translateY(-3px);box-shadow:0 6px 18px rgba(0,0,0,.08)}
.kpi-val{font-size:1.55rem;font-weight:800;letter-spacing:-.5px}
.kpi-ico{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
.crec-up{color:#15803d;font-weight:700}.crec-down{color:#b91c1c;font-weight:700}
.rank{width:26px;height:26px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700}
.progress-sm{height:6px;border-radius:4px}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand d-flex align-items-center text-white" href="../dashboard.php">
      <i class="bi bi-shop text-warning me-2 fs-3"></i>
      <span class="fw-bold">Tienda</span><span class="text-warning fw-light">Insumo</span>
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto ms-3">
        <li class="nav-item"><a class="nav-link nav-link-custom" href="../dashboard.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="contabilidad_full.php"><i class="bi bi-calculator me-1"></i> Contabilidad</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom active" href="estadisticas.php"><i class="bi bi-bar-chart-line me-1"></i> Estadísticas</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="bg-white border-bottom py-3 mb-4">
  <div class="container-fluid px-5">
    <ol class="breadcrumb small mb-1">
      <li class="breadcrumb-item"><a href="../dashboard.php">Inicio</a></li>
      <li class="breadcrumb-item"><a href="contabilidad_full.php">Contabilidad</a></li>
      <li class="breadcrumb-item active">Estadísticas</li>
    </ol>
    <h3 class="fw-bold mb-0"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Estadísticas del Sistema</h3>
    <p class="text-muted small mb-0">Análisis visual completo de ventas, gastos, clientes y tendencias</p>
  </div>
</header>

<main class="container-fluid px-5 pb-5">

  <!-- KPIs rápidos -->
  <section class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="kpi">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold mb-1 text-uppercase">Ticket Promedio</p>
            <div class="kpi-val text-primary">$<?=number_format($ticket['promedio'],0,',','.')?></div>
            <small class="text-muted">Por venta</small>
          </div>
          <div class="kpi-ico bg-primary bg-opacity-10 text-primary"><i class="bi bi-receipt-cutoff"></i></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold mb-1 text-uppercase">Venta Máxima</p>
            <div class="kpi-val text-success">$<?=number_format($ticket['maximo'],0,',','.')?></div>
            <small class="text-muted">En una transacción</small>
          </div>
          <div class="kpi-ico bg-success bg-opacity-10 text-success"><i class="bi bi-trophy-fill"></i></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold mb-1 text-uppercase">Mes Actual</p>
            <div class="kpi-val text-dark">$<?=number_format($crec['mes_actual'],0,',','.')?></div>
            <small class="<?=$crec['porcentaje']>=0?'text-success':'text-danger'?> fw-semibold">
              <?=$crec['porcentaje']>=0?'▲':'▼'?> <?=abs($crec['porcentaje'])?>% vs mes anterior
            </small>
          </div>
          <div class="kpi-ico bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-month"></i></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small fw-semibold mb-1 text-uppercase">Mes Anterior</p>
            <div class="kpi-val text-secondary">$<?=number_format($crec['mes_anterior'],0,',','.')?></div>
            <small class="text-muted">Comparativo</small>
          </div>
          <div class="kpi-ico bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-calendar-check"></i></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gráfica principal: Flujo 12 meses -->
  <section class="row g-4 mb-4">
    <div class="col-12">
      <div class="stat-card">
        <h6 class="fw-bold mb-1"><i class="bi bi-activity text-success me-2"></i>Ingresos vs Egresos — Últimos 12 Meses</h6>
        <p class="text-muted small mb-4">Comparativa mensual de ventas completadas y gastos registrados</p>
        <canvas id="chartFlujo12" height="70"></canvas>
      </div>
    </div>
  </section>

  <!-- Gráficas secundarias -->
  <section class="row g-4 mb-4">
    <!-- Ventas por día -->
    <div class="col-lg-6">
      <div class="stat-card">
        <h6 class="fw-bold mb-1"><i class="bi bi-calendar-week text-primary me-2"></i>Ventas por Día de la Semana</h6>
        <p class="text-muted small mb-4">Últimos 30 días — cuándo se vende más</p>
        <canvas id="chartDias" height="140"></canvas>
      </div>
    </div>
    <!-- Métodos de pago -->
    <div class="col-lg-6">
      <div class="stat-card">
        <h6 class="fw-bold mb-1"><i class="bi bi-credit-card text-warning me-2"></i>Distribución por Método de Pago</h6>
        <p class="text-muted small mb-4">Preferencia de pago de los clientes</p>
        <div class="row align-items-center">
          <div class="col-7"><canvas id="chartMetodos" height="180"></canvas></div>
          <div class="col-5">
            <?php foreach($stats['metodos'] as $m): ?>
            <div class="mb-2">
              <div class="d-flex justify-content-between small"><span class="fw-semibold"><?=htmlspecialchars($m['metodo_pago'])?></span><span class="text-muted"><?=$m['cantidad']?> ventas</span></div>
              <div class="fw-bold text-success small">$<?=number_format($m['total'],0,',','.')?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="row g-4 mb-4">
    <!-- Top 5 clientes -->
    <div class="col-lg-7">
      <div class="stat-card">
        <h6 class="fw-bold mb-4"><i class="bi bi-people-fill text-success me-2"></i>Top 5 Clientes por Facturación</h6>
        <?php
        $maxC = !empty($stats['top_clientes']) ? max(array_column($stats['top_clientes'],'total')) : 1;
        $colores = ['success','primary','warning','info','secondary'];
        foreach($stats['top_clientes'] as $i => $c):
          $pct = $maxC > 0 ? round($c['total']/$maxC*100) : 0;
        ?>
        <div class="d-flex align-items-center gap-3 mb-3">
          <span class="rank bg-<?=$colores[$i]?> bg-opacity-15 text-<?=$colores[$i]?>"><?=$i+1?></span>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between mb-1">
              <span class="fw-semibold small"><?=htmlspecialchars($c['nombre'])?></span>
              <span class="small text-muted"><?=$c['pedidos']?> pedidos · <strong class="text-dark">$<?=number_format($c['total'],0,',','.')?></strong></span>
            </div>
            <div class="progress progress-sm"><div class="progress-bar bg-<?=$colores[$i]?>" style="width:<?=$pct?>%"></div></div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($stats['top_clientes'])): ?><p class="text-muted text-center py-3">Sin datos disponibles</p><?php endif; ?>
      </div>
    </div>

    <!-- Gastos por categoría -->
    <div class="col-lg-5">
      <div class="stat-card">
        <h6 class="fw-bold mb-1"><i class="bi bi-wallet2 text-danger me-2"></i>Gastos por Categoría</h6>
        <p class="text-muted small mb-4">Distribución de egresos operativos</p>
        <?php if(!empty($stats['gastos_cat'])): ?>
        <canvas id="chartGastos" height="180"></canvas>
        <div class="mt-3">
          <?php foreach($stats['gastos_cat'] as $g): ?>
          <div class="d-flex justify-content-between small mb-1">
            <span><?=htmlspecialchars($g['categoria'])?></span>
            <span class="fw-bold text-danger">$<?=number_format($g['total'],0,',','.')?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?><p class="text-muted text-center py-4"><i class="bi bi-inbox fs-2"></i><br>Sin gastos registrados</p><?php endif; ?>
      </div>
    </div>
  </section>

  <!-- CxC por estado -->
  <?php if(!empty($stats['cxc_estados'])): ?>
  <section class="row g-4 mb-4">
    <div class="col-12">
      <div class="stat-card">
        <h6 class="fw-bold mb-4"><i class="bi bi-hourglass-split text-warning me-2"></i>Estado de Cuentas por Cobrar</h6>
        <div class="row g-3">
          <?php
          $colMap = ['Pendiente'=>'warning','Parcial'=>'primary','Pagada'=>'success','Vencida'=>'danger','Anulada'=>'secondary'];
          foreach($stats['cxc_estados'] as $e):
            $col = $colMap[$e['estado']] ?? 'secondary';
          ?>
          <div class="col-6 col-md-3">
            <div class="text-center p-3 bg-<?=$col?> bg-opacity-10 rounded-3 border border-<?=$col?> border-opacity-25">
              <div class="fw-bold fs-4 text-<?=$col?>">$<?=number_format($e['saldo'],0,',','.')?></div>
              <div class="fw-semibold small text-<?=$col?>"><?=$e['estado']?></div>
              <div class="text-muted small"><?=$e['cantidad']?> registros</div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

</main>

<footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary border-opacity-20">
  <div class="container-fluid px-5 text-center"><p class="mb-0">&copy; 2026 <span class="text-white fw-bold">TiendaInsumo</span> — Módulo de Estadísticas</p></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const COLORS = ['#22c55e','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#ec4899'];

// Flujo 12 meses
new Chart(document.getElementById('chartFlujo12'),{
  type:'line',
  data:{labels:<?=$flujo12L?>,datasets:[
    {label:'Ingresos',data:<?=$flujo12I?>,borderColor:'#22c55e',backgroundColor:'rgba(34,197,94,.1)',fill:true,tension:.4,pointRadius:4},
    {label:'Egresos', data:<?=$flujo12E?>,borderColor:'#ef4444',backgroundColor:'rgba(239,68,68,.07)',fill:true,tension:.4,pointRadius:4}
  ]},
  options:{responsive:true,interaction:{mode:'index',intersect:false},plugins:{legend:{position:'top'}},scales:{y:{ticks:{callback:v=>'$'+Number(v).toLocaleString('es-CO')}}}}
});

// Ventas por día
new Chart(document.getElementById('chartDias'),{
  type:'bar',
  data:{labels:<?=$diasL?>,datasets:[{label:'Ingresos ($)',data:<?=$diasV?>,backgroundColor:COLORS.map(c=>c+'55'),borderColor:COLORS,borderWidth:2,borderRadius:8}]},
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>'$'+Number(v).toLocaleString('es-CO')}}}}
});

// Métodos de pago
<?php if(!empty($stats['metodos'])): ?>
new Chart(document.getElementById('chartMetodos'),{
  type:'doughnut',
  data:{labels:<?=$metodosL?>,datasets:[{data:<?=$metodosV?>,backgroundColor:COLORS,borderWidth:0}]},
  options:{cutout:'60%',plugins:{legend:{display:false}}}
});
<?php endif; ?>

// Gastos categoría
<?php if(!empty($stats['gastos_cat'])): ?>
new Chart(document.getElementById('chartGastos'),{
  type:'polarArea',
  data:{labels:<?=$gastosL?>,datasets:[{data:<?=$gastosV?>,backgroundColor:COLORS.map(c=>c+'99')}]},
  options:{plugins:{legend:{position:'bottom',labels:{font:{size:10}}}}}
});
<?php endif; ?>
</script>
</body>
</html>
