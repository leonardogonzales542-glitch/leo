<?php
/**
 * TiendaInsumo - Sistema de Gestión
 * Controlador: Registro de Usuarios
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'registrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = trim($_POST['nombre']   ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $rol      = trim($_POST['rol']      ?? 'cliente');

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password)) {
        echo json_encode(['ok' => false, 'error' => 'Todos los campos son requeridos.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['ok' => false, 'error' => 'Correo electrónico no válido.']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['ok' => false, 'error' => 'La contraseña debe tener mínimo 6 caracteres.']);
        exit;
    }

    $roles_validos = ['cliente', 'vendedor', 'admin'];
    if (!in_array($rol, $roles_validos)) {
        $rol = 'cliente';
    }

    try {
        $db   = new Database();
        $conn = $db->getConnection();

        // Verificar si el correo ya existe
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            echo json_encode(['ok' => false, 'error' => 'El correo ya está registrado.']);
            exit;
        }

        // Hashear contraseña
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario
        $stmt = $conn->prepare(
            "INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES (?, ?, ?, ?, 'Activo')"
        );
        $ok = $stmt->execute([$nombre, $email, $hash, $rol]);

        if ($ok) {
            echo json_encode([
                'ok'      => true,
                'mensaje' => '¡Registro exitoso! Redirigiendo...',
                'usuario' => ['nombre' => $nombre, 'email' => $email, 'rol' => $rol]
            ]);
        } else {
            echo json_encode(['ok' => false, 'error' => 'No se pudo registrar el usuario.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['ok' => false, 'error' => 'Acción no válida o método incorrecto.']);
}
