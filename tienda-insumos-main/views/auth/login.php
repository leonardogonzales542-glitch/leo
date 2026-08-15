<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AgriStock</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310b981'%3E%3Cpath d='M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z'/%3E%3C/svg%3E">
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom Style Sheet -->
    <link href="../../public/css/style.css" rel="stylesheet">
    <!-- Framework Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="auth-page">

    <div class="auth-card">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a class="text-success fw-bold text-decoration-none fs-2 d-inline-flex align-items-center mb-2" href="../../public/index.php">
                <i class="bi bi-patch-check-fill text-success me-2"></i>AgriStock
            </a>
            <h4 class="fw-bold mb-1 text-white">¡Bienvenido de nuevo!</h4>
            <p class="auth-subtitle small">Ingresa tus credenciales para acceder al sistema</p>
        </div>

        <!-- Form -->
        <form class="needs-validation" novalidate action="../../controllers/auth/authController.php" method="POST">
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label-premium">Correo Electrónico</label>
                <div class="input-group input-group-premium">
                    <span class="input-group-text input-group-text-premium"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control form-control-premium" id="email" name="email" placeholder="nombre@ejemplo.com" required autocomplete="email">
                    <div class="invalid-feedback">Por favor ingresa un correo electrónico válido.</div>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label-premium mb-0">Contraseña</label>
                    <a href="#" class="small text-link">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="input-group input-group-premium">
                    <span class="input-group-text input-group-text-premium"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control form-control-premium" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    <div class="invalid-feedback">Por favor ingresa tu contraseña.</div>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-4 form-check d-flex align-items-center gap-2">
                <input type="checkbox" class="form-check-input bg-transparent border-secondary" id="remember" name="remember">
                <label class="form-check-label text-secondary small" for="remember">Recordarme en este dispositivo</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-premium btn-premium-primary w-100 py-3 mb-3">
                Iniciar Sesión <i class="bi bi-box-arrow-in-right ms-2"></i>
            </button>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-secondary small mb-0">
                    ¿No tienes una cuenta? <a href="register.php" class="text-link">Regístrate aquí</a>
                </p>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5.3.2 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form Validation Bootstrap Script
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

    <?php if (isset($_SESSION['alert'])): ?>
    <script>
        Swal.fire({
            icon: '<?php echo $_SESSION['alert']['icon']; ?>',
            title: '<?php echo $_SESSION['alert']['title']; ?>',
            text: '<?php echo $_SESSION['alert']['text']; ?>',
            confirmButtonColor: '#10b981',
            background: '#1e293b',
            color: '#fff'
        });
    </script>
    <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
</body>
</html>
