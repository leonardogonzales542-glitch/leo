<?php
session_start();
require_once 'config/database.php';

// Primero asegurar que el admin existe
$usuario = "Administrador Principal";
$email = "admin@agristock.com";
$password = "admin123";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$id_rol = 1;

$check = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");
if ($check->num_rows == 0) {
    $sql = "INSERT INTO usuarios (usuario, email, password, id_rol, estado, fecha_creacion) VALUES ('$usuario', '$email', '$hashed_password', $id_rol, 'Activo', NOW())";
    $conn->query($sql);
}

// Obtener el ID del admin
$admin = $conn->query("SELECT * FROM usuarios WHERE email = '$email'")->fetch_assoc();

// Iniciar sesión a la fuerza
$_SESSION['usuario'] = [
    'id_usuario' => $admin['id_usuario'],
    'usuario' => $admin['usuario'],
    'email' => $admin['email'],
    'id_rol' => $admin['id_rol']
];

// Redirigir al panel de administración
header("Location: views/admin/dashboard.php");
exit;
?>
