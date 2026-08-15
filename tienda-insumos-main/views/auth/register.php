<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AgriStock</title>
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

    <div class="auth-card auth-card-register">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a class="text-success fw-bold text-decoration-none fs-2 d-inline-flex align-items-center mb-2" href="../../public/index.php">
                <i class="bi bi-patch-check-fill text-success me-2"></i>AgriStock
            </a>
            <h4 class="fw-bold mb-1 text-white">Crear una Cuenta</h4>
            <p class="auth-subtitle small">Regístrate para empezar a gestionar tus insumos agrícolas</p>
        </div>

        <!-- Form -->
        <form class="needs-validation" novalidate action="../../controllers/auth/registerController.php" method="POST" id="registerForm">
            <!-- Usuario (Nombre de Usuario) -->
            <div class="mb-3">
                <label for="usuario" class="form-label-premium">Nombre de Usuario</label>
                <div class="input-group input-group-premium">
                    <span class="input-group-text input-group-text-premium"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control form-control-premium" id="usuario" name="usuario" placeholder="juanperez123" required autocomplete="username">
                    <div class="invalid-feedback">Por favor ingresa tu nombre de usuario.</div>
                </div>
            </div>

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
                <label for="password" class="form-label-premium">Contraseña</label>
                <div class="input-group input-group-premium">
                    <span class="input-group-text input-group-text-premium"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control form-control-premium" id="password" name="password" placeholder="••••••••" required autocomplete="new-password" minlength="8">
                    <div class="invalid-feedback">Debe tener al menos 8 caracteres.</div>
                </div>
            </div>

            <!-- Terms and Conditions -->  
            <div class="mb-4 form-check d-flex align-items-start gap-2">
                <input type="checkbox" class="form-check-input bg-transparent border-secondary mt-1" id="terms" name="terms" required>
                <label class="form-check-label text-secondary small" for="terms">
                    Acepto los <a href="#" class="text-link">Términos de Servicio</a> y la <a href="#" class="text-link">Política de Privacidad</a> de AgriStock.
                </label>
                <div class="invalid-feedback">Debes aceptar los términos para registrarte.</div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-premium btn-premium-primary w-100 py-3 mb-3">
                Registrarse <i class="bi bi-check-circle ms-2"></i>
            </button>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-secondary small mb-0">
                    ¿Ya tienes una cuenta? <a href="login.php" class="text-link">Inicia sesión aquí</a>
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
            var form = document.getElementById('registerForm')

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })()
    </script>
</body>
</html>
