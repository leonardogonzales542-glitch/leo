<?php
session_start();
require_once '../config/database.php';
require_once '../models/pedido.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$pedidoModel = new Pedido($conn);
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Attempt to parse action from JSON input if empty
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
    $sql = "SELECT id_producto, codigo, nombre, precio_venta as precio, stock_actual FROM productos WHERE estado = 1";
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

// Petición POST para guardar el pedido
if ($action === 'save_pedido') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
        exit;
    }

    $id_cliente = $data['id_cliente'];
    $id_vendedor = $_SESSION['usuario']['id_usuario'];
    $metodo_pago = $data['metodo_pago'];
    $entrega = $data['entrega'];
    $subtotal = $data['subtotal'];
    $iva = 0; // Configurable
    $total = $data['total'];
    $detalles = $data['detalles'];

    if (empty($id_cliente) || empty($detalles) || $total <= 0) {
        echo json_encode(["status" => "error", "message" => "Faltan datos del pedido o carrito vacío."]);
        exit;
    }

    $id_pedido = $pedidoModel->createPedido($id_cliente, $id_vendedor, $subtotal, $iva, $total, $metodo_pago, $entrega, $detalles);

    if (is_numeric($id_pedido)) {
        echo json_encode(["status" => "success", "id_pedido" => $id_pedido]);
    } else {
        echo json_encode(["status" => "error", "message" => $id_pedido['error'] ?? "Error desconocido al procesar el pedido."]);
    }
    exit;
}

// Petición GET o POST para cambiar estado
if ($action === 'update_estado') {
    $id_pedido = $_POST['id_pedido'] ?? 0;
    $estado = $_POST['estado'] ?? '';
    if ($pedidoModel->updateEstado($id_pedido, $estado)) {
        $_SESSION['success'] = "Estado del pedido actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el estado.";
    }
    header('Location: ../views/admin/pedidos.php');
    exit;
}
?>
