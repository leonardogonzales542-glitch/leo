<?php
session_start();
require_once '../config/database.php';
require_once '../models/cliente.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../views/auth/login.php');
    exit;
}

$clienteModel = new Cliente($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $tipo_documento = trim($_POST['tipo_documento']);
        $numero_documento = trim($_POST['numero_documento']);
        $nombre = trim($_POST['nombre']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $email = trim($_POST['email']);

        try {
            if ($clienteModel->create($tipo_documento, $numero_documento, $nombre, $telefono, $direccion, $email)) {
                $_SESSION['success'] = "Cliente registrado correctamente.";
            } else {
                $_SESSION['error'] = "Error al registrar cliente. ¿Número de documento duplicado?";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error en la base de datos: Documento o Email ya existen.";
        }
        header('Location: ../views/admin/clientes.php');
        exit;
    } elseif ($action === 'update') {
        $id = (int)$_POST['id_cliente'];
        $tipo_documento = trim($_POST['tipo_documento']);
        $numero_documento = trim($_POST['numero_documento']);
        $nombre = trim($_POST['nombre']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $email = trim($_POST['email']);
        $estado = isset($_POST['estado']) ? 1 : 0;

        try {
            if ($clienteModel->update($id, $tipo_documento, $numero_documento, $nombre, $telefono, $direccion, $email, $estado)) {
                $_SESSION['success'] = "Datos del cliente actualizados.";
            } else {
                $_SESSION['error'] = "Error al actualizar.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: Documento duplicado con otro cliente.";
        }
        header('Location: ../views/admin/clientes.php');
        exit;
    }
}

if ($action === 'delete') {
    $id = (int)$_GET['id'];
    try {
        if ($clienteModel->delete($id)) {
            $_SESSION['success'] = "Cliente eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar. Puede tener ventas asociadas.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "No se puede eliminar el cliente porque ya tiene ventas registradas.";
    }
    header('Location: ../views/admin/clientes.php');
    exit;
}
?>
