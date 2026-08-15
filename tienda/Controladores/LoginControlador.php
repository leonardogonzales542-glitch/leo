<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

$error = '';
$success = '';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email === false || $password === '') {
        $error = 'Por favor, complete todos los campos obligatorios con un correo válido.';
    } else {
        try {
            $stmt = $conn->prepare('SELECT id, nombre, email, rol, password, estado FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || $user['estado'] !== 'Activo' || !password_verify($password, $user['password'])) {
                $error = 'Correo o contraseña incorrectos.';
            } else {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol'],
                ];

                header('Location: ../index.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            $error = 'Ocurrió un error al iniciar sesión. Intente nuevamente más tarde.';
        }
    }
}

include __DIR__ . '/../Vistas/login.php';
