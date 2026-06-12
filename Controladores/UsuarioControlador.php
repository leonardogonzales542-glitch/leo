<?php
/**
 * TiendaInsumo - Sistema de Gestión
 * Controlador: Gestión de Usuarios (Login, Perfil, Lista)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

require_once __DIR__ . '/../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$db   = new Database();
$conn = $db->getConnection();

// ── LOGIN ──────────────────────────────────────────────────
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(['ok' => false, 'error' => 'Correo y contraseña son requeridos.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND estado = 'Activo' LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            echo json_encode(['ok' => false, 'error' => 'Credenciales incorrectas.']);
            exit;
        }

        // Guardar sesión
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        echo json_encode([
            'ok'      => true,
            'mensaje' => 'Inicio de sesión exitoso.',
            'usuario' => [
                'id'     => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email'  => $usuario['email'],
                'rol'    => $usuario['rol']
            ]
        ]);

    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
    }

// ── LOGOUT ─────────────────────────────────────────────────
} elseif ($action === 'logout') {

    session_destroy();
    echo json_encode(['ok' => true, 'mensaje' => 'Sesión cerrada correctamente.']);

// ── PERFIL (sesión activa) ──────────────────────────────────
} elseif ($action === 'perfil') {

    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['ok' => false, 'error' => 'No hay sesión activa.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id, nombre, email, rol, estado, creado_en FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        $usuario = $stmt->fetch();

        echo json_encode(['ok' => true, 'data' => $usuario]);

    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }

// ── LISTAR (solo admin) ─────────────────────────────────────
} elseif ($action === 'listar') {

    if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
        echo json_encode(['ok' => false, 'error' => 'Acceso denegado. Se requiere rol admin.']);
        exit;
    }

    try {
        $stmt = $conn->query("SELECT id, nombre, email, rol, estado, creado_en FROM usuarios ORDER BY creado_en DESC");
        echo json_encode(['ok' => true, 'data' => $stmt->fetchAll()]);

    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }

// ── CAMBIAR CONTRASEÑA ──────────────────────────────────────
} elseif ($action === 'cambiar_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['ok' => false, 'error' => 'No hay sesión activa.']);
        exit;
    }

    $password_actual = trim($_POST['password_actual'] ?? '');
    $password_nueva  = trim($_POST['password_nueva']  ?? '');

    if (empty($password_actual) || empty($password_nueva)) {
        echo json_encode(['ok' => false, 'error' => 'Ambas contraseñas son requeridas.']);
        exit;
    }

    if (strlen($password_nueva) < 6) {
        echo json_encode(['ok' => false, 'error' => 'La nueva contraseña debe tener mínimo 6 caracteres.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($password_actual, $row['password'])) {
            echo json_encode(['ok' => false, 'error' => 'La contraseña actual no es correcta.']);
            exit;
        }

        $hash = password_hash($password_nueva, PASSWORD_BCRYPT);
        $upd  = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $upd->execute([$hash, $_SESSION['usuario_id']]);

        echo json_encode(['ok' => true, 'mensaje' => 'Contraseña actualizada correctamente.']);

    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }

// ── CAMBIAR ESTADO (solo admin) ─────────────────────────────
} elseif ($action === 'cambiar_estado' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
        echo json_encode(['ok' => false, 'error' => 'Acceso denegado.']);
        exit;
    }

    $id     = (int)($_POST['id']     ?? 0);
    $estado = trim($_POST['estado']  ?? '');

    if (!$id || !in_array($estado, ['Activo', 'Inactivo'])) {
        echo json_encode(['ok' => false, 'error' => 'Datos inválidos.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
        echo json_encode(['ok' => true, 'mensaje' => "Usuario $estado correctamente."]);

    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }

// ── SESIÓN ACTIVA (verificar) ───────────────────────────────
} elseif ($action === 'verificar_sesion') {

    if (isset($_SESSION['usuario_id'])) {
        echo json_encode([
            'ok'      => true,
            'activo'  => true,
            'usuario' => [
                'id'     => $_SESSION['usuario_id'],
                'nombre' => $_SESSION['usuario_nombre'],
                'email'  => $_SESSION['usuario_email'],
                'rol'    => $_SESSION['usuario_rol']
            ]
        ]);
    } else {
        echo json_encode(['ok' => true, 'activo' => false]);
    }

} else {
    echo json_encode(['ok' => false, 'error' => 'Acción no válida.']);
}
