<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Vista: Registro de Usuarios
 */

$error = "";
$success = "";

// Simulación de Registro (Al hacer POST, simula éxito y redirige a login)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($nombre) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Las contraseñas ingresadas no coinciden.";
        } else {
            $success = "¡Registro exitoso! Redirigiendo al inicio de sesión...";
            // Redirigir después de 2 segundos para dar tiempo a ver el mensaje de éxito
            header("refresh:2;url=login.php");
        }
    } else {
        $error = "Por favor, complete todos los campos obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PurinaStock</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="auth-wrapper">

    <div class="container-fluid px-4 px-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="auth-split-card row g-0">
                    
                    <!-- Lado Izquierdo: Marca e Información -->
                    <div class="col-lg-6 auth-left-brand">
                        <div>
                            <div class="d-flex align-items-center text-white mb-4">
                                <i class="bi bi-dog-fill text-warning me-2 fs-1"></i>
                                <span class="auth-logo fw-bold">Purina</span><span class="auth-logo text-warning fw-light">Stock</span>
                            </div>
                            <h2 class="display-6 fw-bold text-white mb-3">Únete al Panel Administrativo</h2>
                            <p class="text-white-50 lead mb-0">Crea tu cuenta de administrador y empieza a controlar el inventario de insumos y purinas de forma centralizada.</p>
                        </div>
                        <div class="mt-5">
                            <div class="d-flex align-items-center text-white-50 gap-2 mb-2">
                                <i class="bi bi-patch-check text-warning fs-5"></i>
                                <span class="small">Control de accesos y roles</span>
                            </div>
                            <div class="d-flex align-items-center text-white-50 gap-2">
                                <i class="bi bi-hdd-network text-warning fs-5"></i>
                                <span class="small">Copias de seguridad integradas</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado Derecho: Formulario de Registro -->
                    <div class="col-lg-6 auth-right-form bg-white">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-1">Registrar Cuenta</h3>
                            <p class="text-muted small">Crea tu credencial de usuario para ingresar al sistema</p>
                        </div>
                        
                        <?php if ($error !== ""): ?>
                            <div class="alert alert-danger rounded-3 p-3 mb-4 shadow-sm" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success !== ""): ?>
                            <div class="alert alert-success rounded-3 p-3 mb-4 shadow-sm" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <form action="register.php" method="POST">
                            
                            <!-- Nombre Completo -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Nombre Completo</label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-person"></i>
                                    <input type="text" name="nombre" class="form-control form-control-custom text-dark" placeholder="Ej. Leonardo González" required>
                                </div>
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Correo Electrónico</label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email" name="email" class="form-control form-control-custom text-dark" placeholder="tu@correo.com" required>
                                </div>
                            </div>
                            
                            <!-- Contraseña -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Contraseña</label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" name="password" class="form-control form-control-custom text-dark" placeholder="Crea tu contraseña (mínimo 6 caracteres)" minlength="6" required>
                                </div>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Confirmar Contraseña</label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-shield-lock"></i>
                                    <input type="password" name="confirm_password" class="form-control form-control-custom text-dark" placeholder="Confirma tu contraseña anterior" minlength="6" required>
                                </div>
                            </div>

                            <!-- Aceptar Términos -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="termsCheck" required>
                                    <label class="form-check-label text-muted small" for="termsCheck">Acepto los términos y condiciones de seguridad del sistema</label>
                                </div>
                            </div>

                            <!-- Botón Registro -->
                            <button type="submit" class="btn btn-auth-submit w-100 py-3 text-white">
                                <i class="bi bi-check-circle me-2"></i> Crear Cuenta
                            </button>

                            <!-- Enlace Login -->
                            <div class="text-center mt-4">
                                <span class="text-muted small">¿Ya tienes cuenta registrada? </span>
                                <a href="login.php" class="text-warning text-decoration-none small fw-semibold">Inicia Sesión aquí</a>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
