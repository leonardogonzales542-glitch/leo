<?php
session_start();
require_once '../config/database.php';
require_once '../models/producto.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../views/auth/login.php');
    exit;
}

$productoModel = new Producto($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $codigo = trim($_POST['codigo']);
        $nombre = trim($_POST['nombre']);
        $id_categoria = (int)$_POST['id_categoria'];
        $descripcion = trim($_POST['descripcion']);
        $unidad_medida = trim($_POST['unidad_medida']);
        $precio_compra = (float)$_POST['precio_compra'];
        $precio_venta = (float)$_POST['precio_venta'];
        $stock_actual = (float)$_POST['stock_actual'];
        $stock_minimo = (float)$_POST['stock_minimo'];

        $imagen_nombre = 'default.png';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $dir = '../public/img/productos/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $imagen_nombre = time() . '_' . rand(1000, 9999) . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $imagen_nombre);
        }

        try {
            if ($productoModel->create($codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $imagen_nombre)) {
                $_SESSION['success'] = "Producto '$nombre' creado correctamente.";
                header('Location: ../views/admin/inventario.php');
                exit;
            } else {
                $_SESSION['error'] = "Error al crear producto. ¿Código duplicado?";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        header('Location: ../views/admin/agregar_producto.php');
        exit;
    } elseif ($action === 'update') {
        $id = (int)$_POST['id_producto'];
        $codigo = trim($_POST['codigo']);
        $nombre = trim($_POST['nombre']);
        $id_categoria = (int)$_POST['id_categoria'];
        $descripcion = trim($_POST['descripcion']);
        $unidad_medida = trim($_POST['unidad_medida']);
        $precio_compra = (float)$_POST['precio_compra'];
        $precio_venta = (float)$_POST['precio_venta'];
        $stock_actual = (float)$_POST['stock_actual'];
        $stock_minimo = (float)$_POST['stock_minimo'];
        $estado = isset($_POST['estado']) ? 1 : 0;

        $imagen_nombre = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $dir = '../public/img/productos/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $imagen_nombre = time() . '_' . rand(1000, 9999) . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $imagen_nombre);
        }

        try {
            if ($productoModel->update($id, $codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $estado, $imagen_nombre)) {
                $_SESSION['success'] = "Producto actualizado.";
            } else {
                $_SESSION['error'] = "Error al actualizar el producto.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error de base de datos.";
        }
        header('Location: ../views/admin/inventario.php');
        exit;
    }
}

if ($action === 'delete') {
    $id = (int)$_GET['id'];
    try {
        if ($productoModel->delete($id)) {
            $_SESSION['success'] = "Producto eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar. Puede estar asociado a ventas o movimientos.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "No se puede eliminar el producto porque tiene movimientos (ventas, entradas, etc).";
    }
    header('Location: ../views/admin/inventario.php');
    exit;
}
?>
