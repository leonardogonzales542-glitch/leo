<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Vista: Inicio de Sesión (Login)
 */

$error = "";

// Simulación de autenticación (Al hacer POST, redirige al panel principal)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Credenciales de prueba ficticias
    if (!empty($email) && !empty($password)) {
        header("Location: index.php");
        exit;
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
    <title>Iniciar Sesión - PurinaStock</title>
    
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
                            <h2 class="display-6 fw-bold text-white mb-3">Gestión Inteligente de Alimentos</h2>
                            <p class="text-white-50 lead mb-0">Controla entradas, salidas, alertas de stock mínimo y facturación en un solo panel profesional y responsivo.</p>
                        </div>
                        <div class="mt-5">
                            <div class="d-flex align-items-center text-white-50 gap-2 mb-2">
                                <i class="bi bi-shield-check text-warning fs-5"></i>
                                <span class="small">Acceso seguro con cifrado SSL</span>
                            </div>
                            <div class="d-flex align-items-center text-white-50 gap-2">
                                <i class="bi bi-cpu text-warning fs-5"></i>
                                <span class="small">Monitoreo en tiempo real</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado Derecho: Formulario de Login -->
                    <div class="col-lg-6 auth-right-form bg-white">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-1">¡Bienvenido de nuevo!</h3>
                            <p class="text-muted small">Ingresa tus credenciales para acceder al sistema administrativo</p>
                        </div>
                        
                        <?php if ($error !== ""): ?>
                            <div class="alert alert-danger rounded-3 p-3 mb-4 shadow-sm" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form action="login.php" method="POST">
                            
                            <!-- Campo Correo -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Correo Electrónico</label>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email" name="email" class="form-control form-control-custom text-dark" placeholder="admin@purinastock.com" required>
                                </div>
                            </div>
                            
                            <!-- Campo Contraseña -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-bold text-dark mb-1">Contraseña</label>
                                    <a href="#" class="text-warning text-decoration-none small fw-semibold">¿Olvidaste tu contraseña?</a>
                                </div>
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" name="password" id="passwordInput" class="form-control form-control-custom text-dark" placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Recordarme -->
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label text-muted small" for="rememberMe">Recordar mi sesión</label>
                                </div>
                            </div>

                            <!-- Botón Enviar -->
                            <button type="submit" class="btn btn-auth-submit w-100 py-3 text-white">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                            </button>

                            <!-- Enlace Registro -->
                            <div class="text-center mt-4">
                                <span class="text-muted small">¿No tienes una cuenta aún? </span>
                                <a href="register.php" class="text-warning text-decoration-none small fw-semibold">Regístrate aquí</a>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
