<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../views/auth/login.php');
    exit;
}

$usuarioModel = new Usuario();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $id_rol = 2; // Vendedor
        
        if ($usuarioModel->emailExiste($email)) {
            $_SESSION['error'] = "El correo electrónico ya está registrado.";
        } else {
            if ($usuarioModel->registrar($nombre, $email, $password, $id_rol, 'Activo')) {
                $_SESSION['success'] = "Vendedor registrado correctamente.";
            } else {
                $_SESSION['error'] = "Error al registrar el vendedor.";
            }
        }
        header('Location: ../views/admin/vendedores.php');
        exit;
    } elseif ($action === 'update') {
        $id = (int)$_POST['id_usuario'];
        $datos = [
            'usuario' => trim($_POST['nombre']),
            'id_rol' => 2,
            'estado' => isset($_POST['estado']) ? 'Activo' : 'Inactivo',
            'email' => trim($_POST['email'])
        ];

        // Validar si email cambió y si existe
        $usuarioActual = $conn->query("SELECT email FROM usuarios WHERE id_usuario = $id")->fetch_assoc();
        if ($usuarioActual['email'] !== $datos['email'] && $usuarioModel->emailExiste($datos['email'])) {
            $_SESSION['error'] = "El nuevo correo ya está en uso por otro usuario.";
        } else {
            $resultado = $usuarioModel->actualizar($id, $datos);
            if ($resultado === true) {
                // Si enviaron un nuevo password, lo actualizamos directamente
                if (!empty($_POST['password'])) {
                    $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
                    $stmt->bind_param("si", $hashed, $id);
                    $stmt->execute();
                }
                $_SESSION['success'] = "Datos del vendedor actualizados.";
            } else {
                $_SESSION['error'] = "Error: " . $resultado;
            }
        }
        header('Location: ../views/admin/vendedores.php');
        exit;
    }
}

if ($action === 'delete') {
    $id = (int)$_GET['id'];
    $resultado = $usuarioModel->eliminar($id);
    if ($resultado === true) {
        $_SESSION['success'] = "Vendedor eliminado exitosamente.";
    } else {
        $_SESSION['error'] = "Error: Este vendedor ya tiene ventas registradas y no puede ser eliminado. Desactiva su cuenta en su lugar.";
    }
    header('Location: ../views/admin/vendedores.php');
    exit;
}
?>
