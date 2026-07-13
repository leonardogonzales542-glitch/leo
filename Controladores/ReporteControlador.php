<?php
require_once __DIR__ . '/../Modelos/Producto.php';
require_once __DIR__ . '/../Modelos/Venta.php';
require_once __DIR__ . '/../Modelos/Contabilidad.php';

$tipo = $_GET['tipo'] ?? 'inventario';

// ── INVENTARIO CSV ──────────────────────────────────────────
if ($tipo === 'inventario') {
    $producto  = new Producto();
    $productos = $producto->getAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=inventario_' . date('Ymd') . '.csv');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
    fputcsv($out, ['Código','Marca','Nombre','Categoría','Peso','Stock','Stock Mín','Precio Compra','Precio Venta','Estado']);
    foreach ($productos as $p) {
        $estado = $p['stock'] == 0 ? 'Sin Stock' : ($p['stock'] < $p['stock_min'] ? 'Stock Bajo' : 'Disponible');
        fputcsv($out, [
            $p['codigo'], $p['marca'], $p['nombre'], $p['categoria'],
            $p['peso'], $p['stock'], $p['stock_min'],
            '$'.$p['precio_compra'], '$'.$p['precio_venta'], $estado
        ]);
    }
    fclose($out);
    exit;

// ── VENTAS CSV ──────────────────────────────────────────────
} elseif ($tipo === 'ventas') {
    $venta  = new Venta();
    $ventas = $venta->getAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=ventas_' . date('Ymd') . '.csv');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($out, ['Código Venta','Fecha','Cliente','Total','Método Pago','Estado']);
    foreach ($ventas as $v) {
        fputcsv($out, [
            $v['codigo_venta'], $v['fecha'], $v['cliente_nombre'],
            '$'.$v['total'], $v['metodo_pago'], $v['estado']
        ]);
    }
    fclose($out);
    exit;

// ── CONTABILIDAD CSV ────────────────────────────────────────
} elseif ($tipo === 'contabilidad') {
    $cont       = new Contabilidad();
    $resumen    = $cont->resumenGeneral();
    $clientes   = $cont->comprasPorCliente();
    $topProd    = $cont->topProductos();
    $porMetodo  = $cont->ventasPorMetodo();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=contabilidad_' . date('Ymd') . '.csv');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

    // Resumen general
    fputcsv($out, ['=== RESUMEN GENERAL ===']);
    fputcsv($out, ['Ingresos Totales', 'Ingresos Hoy', 'Ingresos Semana', 'Ingresos Mes', 'Total Ventas', 'Clientes Activos']);
    fputcsv($out, [
        '$'.$resumen['ingresos_totales'],
        '$'.$resumen['ingresos_hoy'],
        '$'.$resumen['ingresos_semana'],
        '$'.$resumen['ingresos_mes'],
        $resumen['total_ventas'],
        $resumen['clientes_compraron']
    ]);
    fputcsv($out, []);

    // Compras por cliente
    fputcsv($out, ['=== COMPRAS POR CLIENTE ===']);
    fputcsv($out, ['Código','Nombre','Tipo','Email','Teléfono','Total Pedidos','Total Comprado','Última Compra']);
    foreach ($clientes as $c) {
        fputcsv($out, [
            $c['codigo'], $c['nombre'], $c['tipo'],
            $c['email'], $c['telefono'],
            $c['total_pedidos'], '$'.$c['total_comprado'],
            $c['ultima_compra'] ? date('d/m/Y', strtotime($c['ultima_compra'])) : '—'
        ]);
    }
    fputcsv($out, []);

    // Top productos
    fputcsv($out, ['=== TOP PRODUCTOS MÁS VENDIDOS ===']);
    fputcsv($out, ['Marca','Producto','Unidades Vendidas','Total Generado']);
    foreach ($topProd as $p) {
        fputcsv($out, [$p['marca'], $p['nombre'], $p['unidades_vendidas'], '$'.$p['total_generado']]);
    }
    fputcsv($out, []);

    // Métodos de pago
    fputcsv($out, ['=== MÉTODOS DE PAGO ===']);
    fputcsv($out, ['Método','Cantidad de Ventas','Total']);
    foreach ($porMetodo as $m) {
        fputcsv($out, [$m['metodo_pago'], $m['cantidad'], '$'.$m['total']]);
    }

    fclose($out);
    exit;

// ── CONTABILIDAD PDF (impresión desde navegador) ────────────
} elseif ($tipo === 'contabilidad_pdf') {
    $cont      = new Contabilidad();
    $resumen   = $cont->resumenGeneral();
    $clientes  = $cont->comprasPorCliente();
    $topProd   = $cont->topProductos();
    $porMetodo = $cont->ventasPorMetodo();
    $fecha     = date('d/m/Y H:i');
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8"/>
      <title>Reporte de Contabilidad – TiendaInsumo</title>
      <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; padding: 24px; }
        .header { display:flex; justify-content:space-between; align-items:center; border-bottom:3px solid #1e7a38; padding-bottom:14px; margin-bottom:20px; }
        .header h1 { font-size:20px; color:#1e7a38; }
        .header p  { font-size:10px; color:#666; }
        .logo { font-size:22px; font-weight:900; color:#1e7a38; }
        .logo span { color:#145a27; }

        .kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:20px; }
        .kpi  { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px; text-align:center; }
        .kpi .val { font-size:16px; font-weight:900; color:#15803d; }
        .kpi .lbl { font-size:9px; color:#666; text-transform:uppercase; margin-top:2px; }

        section { margin-bottom:22px; }
        section h2 { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#1e7a38; border-bottom:1px solid #bbf7d0; padding-bottom:5px; margin-bottom:10px; }

        table { width:100%; border-collapse:collapse; }
        th { background:#1e7a38; color:#fff; padding:7px 8px; text-align:left; font-size:10px; }
        td { padding:6px 8px; border-bottom:1px solid #e5e7eb; font-size:10px; }
        tr:nth-child(even) td { background:#f9fafb; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .text-green { color:#15803d; font-weight:700; }
        .badge { display:inline-block; padding:2px 8px; border-radius:20px; font-size:9px; font-weight:600; }
        .badge-green { background:#dcfce7; color:#15803d; }
        .badge-blue  { background:#dbeafe; color:#1d4ed8; }
        .badge-yellow{ background:#fef9c3; color:#854d0e; }

        .footer { text-align:center; font-size:9px; color:#9ca3af; margin-top:30px; border-top:1px solid #e5e7eb; padding-top:10px; }

        @media print {
          body { padding:0; }
          .no-print { display:none; }
          @page { margin: 15mm; }
        }
      </style>
    </head>
    <body>

    <!-- Botón imprimir (no aparece en PDF) -->
    <div class="no-print" style="text-align:right;margin-bottom:16px">
      <button onclick="window.print()" style="background:#1e7a38;color:#fff;border:none;padding:10px 24px;border-radius:8px;font-size:12px;cursor:pointer;font-weight:700;">
        🖨️ Imprimir / Guardar como PDF
      </button>
      <a href="javascript:history.back()" style="margin-left:10px;font-size:11px;color:#666">← Volver</a>
    </div>

    <!-- Encabezado -->
    <div class="header">
      <div>
        <div class="logo">Tienda<span>Insumo</span></div>
        <p>Sistema de Gestión de Inventario y Ventas</p>
      </div>
      <div style="text-align:right">
        <strong style="font-size:14px">REPORTE DE CONTABILIDAD</strong><br>
        <p>Generado: <?= $fecha ?></p>
      </div>
    </div>

    <!-- KPIs -->
    <div class="kpis">
      <div class="kpi">
        <div class="val">$<?= number_format($resumen['ingresos_totales'],0,',','.') ?></div>
        <div class="lbl">Ingresos Totales</div>
      </div>
      <div class="kpi">
        <div class="val">$<?= number_format($resumen['ingresos_mes'],0,',','.') ?></div>
        <div class="lbl">Ingresos del Mes</div>
      </div>
      <div class="kpi">
        <div class="val"><?= number_format($resumen['total_ventas'],0) ?></div>
        <div class="lbl">Total Ventas</div>
      </div>
      <div class="kpi">
        <div class="val"><?= number_format($resumen['clientes_compraron'],0) ?></div>
        <div class="lbl">Clientes Activos</div>
      </div>
    </div>

    <!-- Compras por cliente -->
    <section>
      <h2>📊 Compras por Cliente</h2>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th class="text-center">Pedidos</th>
            <th class="text-right">Total Comprado</th>
            <th class="text-center">Última Compra</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($clientes as $i => $c): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><strong><?= htmlspecialchars($c['nombre']) ?></strong><br><span style="color:#9ca3af;font-size:9px"><?= htmlspecialchars($c['email']??'') ?></span></td>
            <td><?= $c['tipo'] ?></td>
            <td class="text-center"><?= $c['total_pedidos'] ?></td>
            <td class="text-right text-green">$<?= number_format($c['total_comprado'],0,',','.') ?></td>
            <td class="text-center"><?= $c['ultima_compra'] ? date('d/m/Y',strtotime($c['ultima_compra'])) : '—' ?></td>
          </tr>
          <?php endforeach; ?>
          <tr style="background:#f0fdf4;font-weight:700">
            <td colspan="4" style="text-align:right;padding-right:10px">TOTAL GENERAL:</td>
            <td class="text-right text-green">$<?= number_format($resumen['ingresos_totales'],0,',','.') ?></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- Top productos -->
    <section>
      <h2>🏆 Top Productos Más Vendidos</h2>
      <table>
        <thead>
          <tr><th>#</th><th>Marca</th><th>Producto</th><th class="text-center">Unidades</th><th class="text-right">Total Generado</th></tr>
        </thead>
        <tbody>
          <?php foreach($topProd as $i => $p): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($p['marca']) ?></td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td class="text-center"><?= $p['unidades_vendidas'] ?></td>
            <td class="text-right text-green">$<?= number_format($p['total_generado'],0,',','.') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Métodos de pago -->
    <section>
      <h2>💳 Métodos de Pago</h2>
      <table>
        <thead>
          <tr><th>Método</th><th class="text-center">Cantidad de Ventas</th><th class="text-right">Total</th></tr>
        </thead>
        <tbody>
          <?php foreach($porMetodo as $m): ?>
          <tr>
            <td><?= htmlspecialchars($m['metodo_pago']) ?></td>
            <td class="text-center"><?= $m['cantidad'] ?></td>
            <td class="text-right text-green">$<?= number_format($m['total'],0,',','.') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <div class="footer">
      © 2026 TiendaInsumo — Reporte generado el <?= $fecha ?> — Documento de uso interno
    </div>

    </body>
    </html>
    <?php
    exit;
}

echo "Tipo de reporte no válido.";
