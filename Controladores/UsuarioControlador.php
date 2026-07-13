```php
<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/auth/register.php');
    exit();
}

try {

    // Sanitizar datos
    $nombreCliente = trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
    $password = $_POST['password'] ?? '';
    $terminos = isset($_POST['terminos']);

    // Validar campos obligatorios
    if (empty($nombreCliente) || empty($email) || empty($password)) {
        throw new Exception('Por favor complete todos los campos obligatorios.');
    }

    // Validar formato del correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Ingrese una dirección de correo electrónico válida.');
    }

    // Validar contraseña
    if (strlen($password) < 8) {
        throw new Exception('La contraseña debe tener al menos 8 caracteres.');
    }

    // Validar términos y condiciones
    if (!$terminos) {
        throw new Exception('Debe aceptar los términos y condiciones para continuar.');
    }

    $usuarioModel = new Usuario();

    // Verificar si el correo ya existe
    if ($usuarioModel->emailExiste($email)) {
        throw new Exception('Ya existe una cuenta registrada con este correo electrónico.');
    }

    // Rol 3 = Cliente
    $registrado = $usuarioModel->registrar(
        $nombreCliente,
        $email,
        $password,
        3
    );

    if (!$registrado) {
        throw new Exception('No fue posible completar el registro. Intente nuevamente.');
    }

    $_SESSION['success'] = 'Registro exitoso. Bienvenido a Purina Store. Ya puede iniciar sesión y realizar sus compras.';

    header('Location: ../../views/auth/login.php');
    exit();

} catch (Exception $e) {

    $_SESSION['error'] = $e->getMessage();

    header('Location: ../../views/auth/register.php');
    exit();
}
?>
```
