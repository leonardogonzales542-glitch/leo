<?php
session_start();
require_once '../config/database.php';
require_once '../models/categoria.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../public/index.php');
    exit;
}

$categoriaModel = new Categoria($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if ($action === 'create') {
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        if ($categoriaModel->create($nombre, $descripcion)) {
            $_SESSION['success'] = "Categoría creada correctamente.";
        } else {
            $_SESSION['error'] = "Error al crear la categoría.";
        }
    } elseif ($action === 'update') {
        $id = $_POST['id_categoria'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $estado = isset($_POST['estado']) ? 1 : 0;
        if ($categoriaModel->update($id, $nombre, $descripcion, $estado)) {
            $_SESSION['success'] = "Categoría actualizada.";
        } else {
            $_SESSION['error'] = "Error al actualizar.";
        }
    }
    header('Location: ../views/admin/categorias.php');
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'];
    try {
        if ($categoriaModel->delete($id)) {
            $_SESSION['success'] = "Categoría eliminada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar. Puede tener productos asociados.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "No se puede eliminar la categoría porque ya tiene productos asociados.";
    }
    header('Location: ../views/admin/categorias.php');
    exit;
}
?>
