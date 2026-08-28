<?php
session_start();
require_once '../config/database.php';
require_once '../models/devolucion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../public/index.php');
    exit;
}

$devolucionModel = new Devolucion($conn);
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

if ($action === 'create') {
    $origen = $_POST['origen']; // 'pedido' o 'venta'
    $id_origen = intval($_POST['id_origen']);
    $motivo = trim($_POST['motivo']);
    
    // Obtener detalles desde los inputs array: productos[id_producto] = cantidad
    $detalles = [];
    if (isset($_POST['productos']) && is_array($_POST['productos'])) {
        foreach ($_POST['productos'] as $id_producto => $cantidad) {
            $cantidad = floatval($cantidad);
            if ($cantidad > 0) {
                $detalles[] = [
                    'id_producto' => intval($id_producto),
                    'cantidad' => $cantidad
                ];
            }
        }
    }

    if (empty($detalles)) {
        $_SESSION['alert'] = [
            'type' => 'warning',
            'title' => 'Atención',
            'message' => 'Debe ingresar al menos un producto a devolver.'
        ];
        header('Location: ../views/admin/devoluciones.php');
        exit;
    }

    $id_pedido = ($origen === 'pedido') ? $id_origen : null;
    $id_venta = ($origen === 'venta') ? $id_origen : null;

    $result = $devolucionModel->createDevolucion($id_pedido, $id_venta, $motivo, $detalles);

    if (is_numeric($result)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => '¡Devolución Procesada!',
            'message' => 'La devolución se ha registrado y el inventario ha sido restablecido.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'No se pudo procesar: ' . (isset($result['error']) ? $result['error'] : 'Error desconocido.')
        ];
    }

    header('Location: ../views/admin/devoluciones.php');
    exit;
}

// Acción por defecto: redirigir si se accede directamente
header('Location: ../views/admin/devoluciones.php');
exit;
?>
