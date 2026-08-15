<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';

$modulo = "Reportes";
$titulo = $modulo;
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1"><?= $modulo ?></h3>
            <span class="text-muted small">Módulo en construcción</span>
        </div>
    </div>
    <div class="alert alert-info border-0 shadow-sm rounded-4 d-flex align-items-center p-4" role="alert" style="background-color: #f8f9fa;">
        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px;">
            <i class="fa-solid fa-tools fs-3 text-info"></i>
        </div>
        <div>
            <h4 class="alert-heading fw-bold mb-1">Página en Desarrollo</h4>
            <p class="mb-0 text-muted">Este módulo se encuentra actualmente en construcción y estará disponible próximamente.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
