<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../public/index.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';

$titulo = 'Registrar Nueva Labor';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';

$alert = '';
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>

<style>
    /* Estilos específicos para replicar el mockup dentro del panel */
    .labor-container {
        background-color: #0b111a;
        color: #f8fafc;
        font-family: 'Inter', sans-serif;
        padding: 2rem;
        border-radius: 12px;
        min-height: calc(100vh - 100px);
    }
    .form-card {
        background-color: #161e2b;
        border-radius: 16px;
        padding: 2.5rem;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .form-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        color: #f8fafc;
    }
    .form-title i {
        background-color: white;
        color: #161e2b;
        border-radius: 50%;
        font-size: 1.2rem;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-volver {
        background-color: transparent;
        color: #f8fafc;
        border: 1px solid #334155;
        padding: 0.5rem 1.25rem;
        border-radius: 2rem;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-volver:hover {
        background-color: #334155;
        color: #f8fafc;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        color: #f8fafc;
    }
    .form-label .required {
        color: #ef4444;
    }
    .form-input {
        width: 100%;
        background-color: #0b111a;
        border: 1px solid #334155;
        color: #f8fafc;
        padding: 0.875rem 1rem;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: #10b981;
    }
    .form-input::placeholder {
        color: #64748b;
    }
    .form-helper {
        display: block;
        color: #94a3b8;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    .valor-calculado {
        font-size: 1.25rem;
        font-weight: 600;
        color: #94a3b8;
        background-color: #0b111a;
        pointer-events: none;
    }
    .btn-submit {
        background-color: #10b981;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        margin-top: 2rem;
        transition: background-color 0.2s;
    }
    .btn-submit:hover {
        background-color: #059669;
    }
    /* Para forzar estilos de calendario oscuro en Chrome */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>

<div class="container-fluid p-0">
    <div class="labor-container">
        <div class="form-card">
            <div class="form-header">
                <h2 class="form-title">
                    <i class="fa-solid fa-plus"></i>
                    Registrar Nueva Labor
                </h2>
                <a href="dashboard.php" class="btn-volver">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

            <form action="../../controllers/LaborController.php" method="POST">
                <div class="form-grid">
                    
                    <div class="form-group">
                        <label class="form-label">Fecha de la labor <span class="required">*</span></label>
                        <input type="date" name="fecha" class="form-input" required value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lote <span class="required">*</span></label>
                        <input type="text" name="lote" class="form-input" placeholder="Ej: Brasilia, Arenal..." required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Trabajo realizado <span class="required">*</span></label>
                        <input type="text" name="trabajo_realizado" class="form-input" placeholder="Ej: Despache C1, proteccion, 4ta Abonada..." required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Trabajadores <span class="required">*</span></label>
                        <input type="text" name="trabajadores" class="form-input" placeholder="Ej: casulla 2jor, Danilo 2jor, Miguel..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Valor Original (Desglose)</label>
                        <input type="text" name="valor_original" id="valor_original" class="form-input" placeholder="Ej: 300.000 + 50.000">
                        <span class="form-helper">Puede escribir sumas para que se calculen automáticamente.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Valor Calculado ($) <span class="required">*</span></label>
                        <input type="text" name="valor_calculado_display" id="valor_calculado_display" class="form-input valor-calculado" value="0.00" readonly tabindex="-1">
                        <input type="hidden" name="valor_calculado" id="valor_calculado" value="0">
                    </div>

                </div>
                
                <button type="submit" class="btn-submit">Guardar Labor</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputOriginal = document.getElementById('valor_original');
    const inputDisplay = document.getElementById('valor_calculado_display');
    const inputHidden = document.getElementById('valor_calculado');

    function calcularTotal(texto) {
        if (!texto || texto.trim() === '') return 0;
        let expresionLimpia = texto.replace(/\./g, '');
        expresionLimpia = expresionLimpia.replace(/[^0-9+\-*/\(\)\s]/g, '');
        try {
            let resultado = Function('"use strict";return (' + expresionLimpia + ')')();
            if (isNaN(resultado) || !isFinite(resultado)) return 0;
            return resultado;
        } catch (e) {
            return 0;
        }
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('es-CO', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(num);
    }

    inputOriginal.addEventListener('input', function() {
        let valor = this.value;
        let total = calcularTotal(valor);
        inputDisplay.value = formatNumber(total);
        inputHidden.value = total;
    });
    
    <?php if (!empty($alert)): ?>
        let alertData = <?= json_encode($alert) ?>;
        Swal.fire({
            icon: alertData.type,
            title: alertData.title,
            text: alertData.message,
            confirmButtonColor: '#10b981'
        });
    <?php endif; ?>
});
</script>
