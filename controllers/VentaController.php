<?php
session_start();
require_once '../config/database.php';
require_once '../models/venta.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$ventaModel = new Venta($conn);
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
if (empty($action)) {
    $json_input = file_get_contents('php://input');
    $json_data = json_decode($json_input, true);
    if (isset($json_data['action'])) {
        $action = $json_data['action'];
    }
}

// Petición AJAX para buscar clientes
if ($action === 'search_client') {
    $q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
    $sql = "SELECT id_cliente, nombre, numero_documento FROM clientes WHERE estado = 1";
    if ($q !== '') {
        $sql .= " AND (nombre LIKE '%$q%' OR numero_documento LIKE '%$q%')";
    }
    $sql .= " LIMIT 20";
    
    $res = $conn->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Petición AJAX para buscar productos
if ($action === 'search_product') {
    $q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
    $sql = "SELECT id_producto, codigo, nombre, precio_venta as precio, stock_actual, imagen FROM productos WHERE estado = 1";
    if ($q !== '') {
        $sql .= " AND (nombre LIKE '%$q%' OR codigo = '$q')";
    }
    $sql .= " LIMIT 20";
    
    $res = $conn->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Petición POST para guardar la venta
if ($action === 'save_venta') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
        exit;
    }

    $id_cliente = $data['id_cliente'];
    $id_vendedor = $_SESSION['usuario']['id_usuario'];
    $metodo_pago = $data['metodo_pago'];
    $subtotal = $data['subtotal'];
    $descuento = $data['descuento'] ?? 0;
    $iva = 0; // Configurable
    $total = $data['total'];
    $detalles = $data['detalles'];

    if (empty($id_cliente) || empty($detalles) || $total <= 0) {
        echo json_encode(["status" => "error", "message" => "Faltan datos de la venta o carrito vacío."]);
        exit;
    }

    $id_venta = $ventaModel->createVenta($id_cliente, $id_vendedor, $subtotal, $descuento, $iva, $total, $metodo_pago, $detalles);

    if (is_numeric($id_venta)) {
        echo json_encode(["status" => "success", "id_venta" => $id_venta]);
    } else {
        echo json_encode(["status" => "error", "message" => $id_venta['error'] ?? "Error desconocido al procesar la venta."]);
    }
    exit;
}

// Petición GET o POST para anular venta
if ($action === 'anular') {
    $id_venta = $_GET['id'] ?? 0;
    if ($ventaModel->anularVenta($id_venta)) {
        $_SESSION['success'] = "Venta anulada correctamente. Se restauró el stock.";
    } else {
        $_SESSION['error'] = "Error al anular la venta.";
    }
    header('Location: ../views/admin/ventas.php');
    exit;
}
?>
