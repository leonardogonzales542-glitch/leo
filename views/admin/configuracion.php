<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../public/index.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/configuracion.php';

$configModel = new Configuracion($conn);
$config = $configModel->getConfig();

$modulo = "Configuración";
$titulo = 'Ajustes del Sistema';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Ajustes Generales</h3>
            <span class="text-muted small">Configura la información básica de tu empresa.</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="../../controllers/ConfiguracionController.php" method="POST">
                <input type="hidden" name="action" value="update">
                
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Datos de la Empresa</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-building text-primary"></i></span>
                            <input type="text" name="nombre_empresa" class="form-control rounded-end-3 bg-light border-0 py-2" value="<?= htmlspecialchars($config['nombre_empresa'] ?? '') ?>" required placeholder="Ej. AgriStock Inc.">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">NIT / Documento</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-id-card text-primary"></i></span>
                            <input type="text" name="nit" class="form-control rounded-end-3 bg-light border-0 py-2" value="<?= htmlspecialchars($config['nit'] ?? '') ?>" placeholder="Ej. 123456789-0">
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Dirección Principal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-map-location-dot text-primary"></i></span>
                            <input type="text" name="direccion" class="form-control rounded-end-3 bg-light border-0 py-2" value="<?= htmlspecialchars($config['direccion'] ?? '') ?>" placeholder="Dirección de la empresa">
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Información de Contacto</h5>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Teléfono / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-phone text-primary"></i></span>
                            <input type="text" name="telefono" class="form-control rounded-end-3 bg-light border-0 py-2" value="<?= htmlspecialchars($config['telefono'] ?? '') ?>" placeholder="Ej. +1 234 567 8900">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Correo Electrónico (Email)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-envelope text-primary"></i></span>
                            <input type="email" name="email" class="form-control rounded-end-3 bg-light border-0 py-2" value="<?= htmlspecialchars($config['email'] ?? '') ?>" placeholder="correo@empresa.com">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                    <button type="reset" class="btn btn-light rounded-3 px-4 fw-medium border shadow-sm text-secondary hover-dark transition-all">Deshacer Cambios</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-5 fw-medium shadow-sm" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Ajustes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['alert']['type'] ?>',
            title: '<?= addslashes($_SESSION['alert']['title']) ?>',
            text: '<?= addslashes($_SESSION['alert']['message']) ?>',
            timer: <?= $_SESSION['alert']['type'] == 'success' ? '3000' : 'null' ?>,
            showConfirmButton: <?= $_SESSION['alert']['type'] == 'success' ? 'false' : 'true' ?>
        });
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
