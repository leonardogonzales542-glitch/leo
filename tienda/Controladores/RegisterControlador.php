<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

$error = '';
$success = '';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $rol = trim($_POST['rol'] ?? 'cliente');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['password_confirm'] ?? '';

    if ($nombre === '' || $email === false || $password === '' || $confirmPassword === '') {
        $error = 'Por favor, complete todos los campos obligatorios.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Las contraseñas ingresadas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        try {
            $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                $error = 'Ya existe una cuenta con ese correo electrónico.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare(
                    'INSERT INTO usuarios (nombre, email, rol, password, estado, creado_en) VALUES (:nombre, :email, :rol, :password, :estado, NOW())'
                );
                $insert->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':rol' => in_array($rol, ['cliente', 'vendedor', 'admin'], true) ? $rol : 'cliente',
                    ':password' => $hashedPassword,
                    ':estado' => 'Activo',
                ]);

                $success = '¡Registro exitoso! Redirigiendo al inicio de sesión...';
                header('refresh:2;url=../Vistas/login.php');
            }
        } catch (PDOException $e) {
            error_log('Register error: ' . $e->getMessage());
            $error = 'Ocurrió un error en el registro. Intente nuevamente más tarde.';
        }
    }
}

include __DIR__ . '/../Vistas/register.php';
