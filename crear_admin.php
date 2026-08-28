<?php
require_once "config/database.php";

$usuario = "Administrador Principal";
$email = "admin@agristock.com";
$password = "admin123";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$id_rol = 1; // Rol de Administrador

// Verificamos si ya existe
$check = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");
if ($check->num_rows > 0) {
    die("<h2 style='color: orange; text-align: center; margin-top: 50px;'>El administrador ya existe. Inicia sesión con:<br>Email: admin@agristock.com<br>Contraseña: admin123</h2>");
}

$sql = "INSERT INTO usuarios (usuario, email, password, id_rol, estado, fecha_creacion) VALUES (?, ?, ?, ?, 'Activo', NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $usuario, $email, $hashed_password, $id_rol);

if ($stmt->execute()) {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: green;'>¡Administrador creado exitosamente!</h1>";
    echo "<p>Usa estas credenciales para entrar al Panel Admin:</p>";
    echo "<h2>Email: <b>admin@agristock.com</b></h2>";
    echo "<h2>Contraseña: <b>admin123</b></h2>";
    echo "<br><a href='views/auth/login.php' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px;'>Ir al Login</a>";
    echo "</div>";
} else {
    echo "<h1 style='color: red;'>Error al crear admin:</h1><p>" . $conn->error . "</p>";
}
?>
