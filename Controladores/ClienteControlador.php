<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../Modelos/Cliente.php';

$cliente = new Cliente();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'listar') {
    echo json_encode(['ok'=>true,'data'=>$cliente->getAll()]);
} elseif ($action === 'crear' && $_SERVER['REQUEST_METHOD']==='POST') {
    $data = [
        'nombre'   => trim($_POST['nombre']),
        'tipo'     => trim($_POST['tipo']),
        'contacto' => trim($_POST['contacto']),
        'telefono' => trim($_POST['telefono']),
        'email'    => trim($_POST['email']),
    ];
    $ok = $cliente->crear($data);
    echo json_encode(['ok'=>$ok]);
} else {
    echo json_encode(['ok'=>false,'error'=>'Acción no válida']);
}
