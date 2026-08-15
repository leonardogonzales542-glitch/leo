<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../Modelos/Producto.php';

$producto = new Producto();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'listar') {
    echo json_encode(['ok'=>true,'data'=>$producto->getAll()]);
} elseif ($action === 'crear' && $_SERVER['REQUEST_METHOD']==='POST') {
    $data = [
        'codigo'        => trim($_POST['codigo']),
        'marca'         => trim($_POST['marca']),
        'nombre'        => trim($_POST['nombre']),
        'categoria'     => trim($_POST['categoria']),
        'peso'          => trim($_POST['peso']),
        'stock'         => (int)$_POST['stock'],
        'stock_min'     => (int)$_POST['stock_min'],
        'precio_compra' => (float)$_POST['precio_compra'],
        'precio_venta'  => (float)$_POST['precio_venta'],
    ];
    $ok = $producto->crear($data);
    echo json_encode(['ok'=>$ok]);
} elseif ($action === 'editar' && $_SERVER['REQUEST_METHOD']==='POST') {
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'codigo'        => trim($_POST['codigo']),
        'marca'         => trim($_POST['marca']),
        'nombre'        => trim($_POST['nombre']),
        'categoria'     => trim($_POST['categoria']),
        'peso'          => trim($_POST['peso']),
        'stock'         => (int)$_POST['stock'],
        'stock_min'     => (int)$_POST['stock_min'],
        'precio_compra' => (float)$_POST['precio_compra'],
        'precio_venta'  => (float)$_POST['precio_venta'],
    ];
    echo json_encode(['ok'=>$producto->editar($id, $data)]);
} elseif ($action === 'stock' && $_SERVER['REQUEST_METHOD']==='POST') {
    $id = (int)($_POST['id'] ?? 0);
    $cantidad = (int)($_POST['cantidad'] ?? 0);
    echo json_encode(['ok'=>$producto->actualizarStock($id, $cantidad)]);
} elseif ($action === 'eliminar' && $_SERVER['REQUEST_METHOD']==='POST') {
    $id = (int)($_POST['id'] ?? 0);
    echo json_encode(['ok'=>$producto->eliminar($id)]);
} elseif ($action === 'stock') {
    $id = (int)($_GET['id'] ?? 0);
    echo json_encode(['stock'=>$producto->getStockDisponible($id)]);
} else {
    echo json_encode(['ok'=>false,'error'=>'Acción no válida']);
}
