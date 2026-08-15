<?php
/**
 * TiendaInsumo – Controlador de Autenticación
 * Acepta peticiones JSON desde login.html / login.php
 */

// Iniciar sesión ANTES de cualquier header
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

// Aceptar preflight OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../Modelos/Usuario.php';

// ── Leer datos: JSON body (fetch) o formulario clásico ──────────────────────
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (str_contains($contentType, 'application/json')) {
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true) ?? [];
} else {
    $input = $_POST;
}

$email    = trim($input['email']    ?? '');
$password =      $input['password'] ?? '';

// ── Validaciones básicas ─────────────────────────────────────────────────────
if (empty($email) || empty($password)) {
    echo json_encode(['ok' => false, 'error' => 'Por favor, ingrese su correo y contraseña.', 'success' => false, 'message' => 'Por favor, ingrese su correo y contraseña.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'error' => 'Por favor, ingrese un correo electrónico válido.', 'success' => false, 'message' => 'Por favor, ingrese un correo electrónico válido.']);
    exit;
}

// ── Consultar usuario en BD ──────────────────────────────────────────────────
try {
    $usuarioModel = new Usuario();
    $user = $usuarioModel->getByEmail($email);

    if (!$user) {
        echo json_encode(['ok' => false, 'error' => 'El correo electrónico o la contraseña son incorrectos.', 'success' => false, 'message' => 'El correo electrónico o la contraseña son incorrectos.']);
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        echo json_encode(['ok' => false, 'error' => 'El correo electrónico o la contraseña son incorrectos.', 'success' => false, 'message' => 'El correo electrónico o la contraseña son incorrectos.']);
        exit;
    }

    $estado = strtolower($user['estado'] ?? 'activo');
    if ($estado !== 'activo') {
        echo json_encode(['ok' => false, 'error' => 'Esta cuenta está inactiva. Contacte al administrador.', 'success' => false, 'message' => 'Esta cuenta está inactiva. Contacte al administrador.']);
        exit;
    }

    // ── Sesión segura ────────────────────────────────────────────────────────
    session_regenerate_id(true);
    $_SESSION['user_id']     = $user['id'];
    $_SESSION['user_nombre'] = $user['nombre'];
    $_SESSION['user_email']  = $user['email'];
    $_SESSION['user_rol']    = $user['rol'];

    echo json_encode([
        'ok'       => true,
        'success'  => true,
        'mensaje'  => '¡Bienvenido, ' . htmlspecialchars($user['nombre']) . '!',
        'message'  => '¡Bienvenido, ' . htmlspecialchars($user['nombre']) . '!',
        'redirect' => 'dashboard.php',
        'rol'      => $user['rol']
    ]);
    exit;

} catch (Exception $e) {
    error_log('[LoginControlador] ' . $e->getMessage());
    echo json_encode(['ok' => false, 'error' => 'Error del servidor: ' . $e->getMessage(), 'success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    exit;
}
