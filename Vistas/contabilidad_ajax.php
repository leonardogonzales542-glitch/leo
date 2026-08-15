<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Modelos/Contabilidad.php';

$cliente_id = (int)($_GET['cliente_id'] ?? 0);
if (!$cliente_id) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

$cont = new Contabilidad();
$data = $cont->historialCliente($cliente_id);
echo json_encode(['ok' => true, 'data' => $data]);
