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
        $id_rol = 1; // Administrador
        
        if ($usuarioModel->emailExiste($email)) {
            $_SESSION['error'] = "El correo electrónico ya está registrado.";
        } else {
            if ($usuarioModel->registrar($nombre, $email, $password, $id_rol, 'Activo')) {
                $_SESSION['success'] = "Administrador registrado correctamente.";
            } else {
                $_SESSION['error'] = "Error al registrar al administrador.";
            }
        }
        header('Location: ../views/admin/administrar.php');
        exit;
    } elseif ($action === 'update') {
        $id = (int)$_POST['id_usuario'];
        $datos = [
            'usuario' => trim($_POST['nombre']),
            'id_rol' => 1,
            'estado' => isset($_POST['estado']) ? 'Activo' : 'Inactivo',
            'email' => trim($_POST['email'])
        ];

        // Validar si email cambió y si existe
        $usuarioActual = $conn->query("SELECT email FROM usuarios WHERE id_usuario = $id")->fetch_assoc();
        if ($usuarioActual['email'] !== $datos['email'] && $usuarioModel->emailExiste($datos['email'])) {
            $_SESSION['error'] = "El nuevo correo ya está en uso por otro usuario.";
        } else {
            // Evitar que el administrador principal se desactive a sí mismo si es el único
            if ($datos['estado'] == 'Inactivo') {
                $countAdminActivos = $conn->query("SELECT COUNT(*) as c FROM usuarios WHERE id_rol = 1 AND estado = 'Activo' AND id_usuario != $id")->fetch_assoc();
                if ($countAdminActivos['c'] == 0) {
                    $_SESSION['error'] = "No puedes desactivar a este administrador porque es el único activo en el sistema.";
                    header('Location: ../views/admin/administrar.php');
                    exit;
                }
            }

            $resultado = $usuarioModel->actualizar($id, $datos);
            if ($resultado === true) {
                // Actualizar password si se envió
                if (!empty($_POST['password'])) {
                    $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
                    $stmt->bind_param("si", $hashed, $id);
                    $stmt->execute();
                }
                
                // Si el usuario se editó a sí mismo, actualizar la sesión
                if ($id == $_SESSION['usuario']['id_usuario']) {
                    $_SESSION['usuario']['usuario'] = $datos['usuario'];
                }
                
                $_SESSION['success'] = "Datos del administrador actualizados.";
            } else {
                $_SESSION['error'] = "Error: " . $resultado;
            }
        }
        header('Location: ../views/admin/administrar.php');
        exit;
    }
}

if ($action === 'delete') {
    $id = (int)$_GET['id'];
    
    // Evitar suicidio digital
    if ($id == $_SESSION['usuario']['id_usuario']) {
        $_SESSION['error'] = "Por razones de seguridad, no puedes eliminar tu propia cuenta.";
        header('Location: ../views/admin/administrar.php');
        exit;
    }

    $resultado = $usuarioModel->eliminar($id);
    if ($resultado === true) {
        $_SESSION['success'] = "Administrador eliminado exitosamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar. Posiblemente hay registros vinculados a este usuario.";
    }
    header('Location: ../views/admin/administrar.php');
    exit;
}
?>
