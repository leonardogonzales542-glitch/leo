<?php
require_once __DIR__ . '/../Modelos/Producto.php';
require_once __DIR__ . '/../Modelos/Venta.php';

$tipo = $_GET['tipo'] ?? 'inventario';
$producto = new Producto();
$venta    = new Venta();

if ($tipo === 'inventario') {
    $productos = $producto->getAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=inventario_' . date('Ymd') . '.csv');
    $out = fopen('php://output','w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
    fputcsv($out, ['Código','Marca','Nombre','Categoría','Peso','Stock','Stock Mín','Precio Compra','Precio Venta']);
    foreach($productos as $p) {
        fputcsv($out, [$p['codigo'],$p['marca'],$p['nombre'],$p['categoria'],$p['peso'],$p['stock'],$p['stock_min'],$p['precio_compra'],$p['precio_venta']]);
    }
    fclose($out); exit;
} elseif ($tipo === 'ventas') {
    $ventas = $venta->getAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=ventas_' . date('Ymd') . '.csv');
    $out = fopen('php://output','w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($out, ['Código Venta','Fecha','Cliente','Total','Método Pago','Estado']);
    foreach($ventas as $v) {
        fputcsv($out, [$v['codigo_venta'],$v['fecha'],$v['cliente_nombre'],$v['total'],$v['metodo_pago'],$v['estado']]);
    }
    fclose($out); exit;
}
echo "Tipo de reporte no válido.";
