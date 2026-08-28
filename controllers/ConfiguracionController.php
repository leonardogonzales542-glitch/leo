<?php
session_start();
require_once '../config/database.php';
require_once '../models/configuracion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../views/auth/login.php');
    exit;
}

$configModel = new Configuracion($conn);

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

if ($action === 'update') {
    $nombre_empresa = trim($_POST['nombre_empresa']);
    $nit = trim($_POST['nit']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);

    if ($configModel->updateConfig($nombre_empresa, $nit, $direccion, $telefono, $email)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => '¡Guardado!',
            'message' => 'La configuración de la empresa ha sido actualizada.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'No se pudo actualizar la configuración.'
        ];
    }
    
    header('Location: ../views/admin/configuracion.php');
    exit;
}
?>
