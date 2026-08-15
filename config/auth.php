<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Middleware de Autenticación y Control de Acceso
 */

if (session_status() === PHP_SESSION_NONE) {
    // Configuración de seguridad para las cookies de sesión
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    
    // Determinar si la conexión es HTTPS para la cookie segura
    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    session_set_cookie_params([
        'lifetime' => 0, // Expira al cerrar el navegador
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

/**
 * Verifica si el usuario ha iniciado sesión.
 * 
 * @return bool
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Fuerza al usuario a estar logueado. Si no, redirige al login.
 * 
 * @param string $loginPagePath Ruta relativa al archivo login.php
 */
function requireLogin(string $loginPagePath = 'login.php'): void {
    if (!isLoggedIn()) {
        header("Location: " . $loginPagePath);
        exit();
    }
}

/**
 * Fuerza a que el usuario tenga uno de los roles autorizados.
 * Si no está autorizado, lo redirige al dashboard con un mensaje.
 * 
 * @param array|string $allowedRoles Rol o lista de roles permitidos
 * @param string $redirectPath Ruta de redirección en caso de no autorizado
 */
function requireRole($allowedRoles, string $redirectPath = 'dashboard.php'): void {
    requireLogin($redirectPath === 'dashboard.php' ? 'login.php' : '../login.php');
    
    $roles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
    $userRole = $_SESSION['user_rol'] ?? '';
    
    if (!in_array($userRole, $roles)) {
        // Redirigir si no tiene el rol
        $_SESSION['auth_error'] = 'No tiene permisos para acceder a esta sección.';
        header("Location: " . $redirectPath);
        exit();
    }
}

/**
 * Retorna los detalles del usuario actual de la sesión.
 * 
 * @return array|null
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'nombre' => $_SESSION['user_nombre'],
        'email' => $_SESSION['user_email'],
        'rol' => $_SESSION['user_rol']
    ];
}
?>
