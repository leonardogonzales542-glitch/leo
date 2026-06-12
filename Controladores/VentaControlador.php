<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../Modelos/Venta.php';
require_once __DIR__ . '/../Modelos/Producto.php';

$venta = new Venta();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'listar') {
    echo json_encode(['ok'=>true,'data'=>$venta->getAll()]);
} elseif ($action === 'crear' && $_SERVER['REQUEST_METHOD']==='POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;
    $cliente_id  = (int)($input['cliente_id'] ?? 0);
    $metodo_pago = trim($input['metodo_pago'] ?? 'Efectivo');
    $items       = $input['items'] ?? [];
    if (!$cliente_id || empty($items)) {
        echo json_encode(['ok'=>false,'error'=>'Datos incompletos']); exit;
    }
    $result = $venta->crear($cliente_id, $metodo_pago, $items);
    echo json_encode($result);
} else {
    echo json_encode(['ok'=>false,'error'=>'Acción no válida']);
}
