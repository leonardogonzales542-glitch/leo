<?php
session_start();
require_once '../config/database.php';
require_once '../models/cupon.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../views/auth/login.php');
    exit;
}

$cuponModel = new Cupon($conn);

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

if ($action === 'create') {
    $codigo = trim($_POST['codigo']);
    $descuento = floatval($_POST['descuento']);
    $tipo = $_POST['tipo'];
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
    $estado = isset($_POST['estado']) ? 1 : 0;

    if ($cuponModel->createCupon($codigo, $descuento, $tipo, $fecha_inicio, $fecha_fin, $estado)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => '¡Éxito!',
            'message' => 'Cupón creado correctamente.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'No se pudo crear el cupón o el código ya existe.'
        ];
    }
    header('Location: ../views/admin/cupones.php');
    exit;
}

if ($action === 'update') {
    $id = intval($_POST['id_cupon']);
    $codigo = trim($_POST['codigo']);
    $descuento = floatval($_POST['descuento']);
    $tipo = $_POST['tipo'];
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
    $estado = isset($_POST['estado']) ? 1 : 0;

    if ($cuponModel->updateCupon($id, $codigo, $descuento, $tipo, $fecha_inicio, $fecha_fin, $estado)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => '¡Actualizado!',
            'message' => 'Cupón modificado correctamente.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'No se pudo actualizar el cupón.'
        ];
    }
    header('Location: ../views/admin/cupones.php');
    exit;
}

if ($action === 'delete') {
    $id = intval($_GET['id']);
    if ($cuponModel->deleteCupon($id)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => '¡Eliminado!',
            'message' => 'El cupón ha sido eliminado.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'No se pudo eliminar el cupón.'
        ];
    }
    header('Location: ../views/admin/cupones.php');
    exit;
}

if ($action === 'get') {
    $id = intval($_GET['id']);
    $cupon = $cuponModel->getCuponById($id);
    header('Content-Type: application/json');
    echo json_encode($cupon);
    exit;
}
?>
