<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../public/index.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/categoria.php';

$categoriaModel = new Categoria($conn);
$categorias = $categoriaModel->getAll();
$categoriasList = [];
if ($categorias && $categorias->num_rows > 0) {
    while($c = $categorias->fetch_assoc()) {
        $categoriasList[] = $c;
    }
}

$titulo = 'Agregar Producto';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 900px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fw-bold text-dark mb-0">Nuevo Producto</h3>
        <a href="inventario.php" class="btn btn-light border shadow-sm px-4 fw-medium text-muted">
            <i class="fa-solid fa-arrow-left me-2"></i>Volver al Inventario
        </a>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5">
            <form action="../../controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                
                <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-box text-primary me-2"></i>Información General</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted small">Código de Barras / SKU *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-barcode text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="codigo" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-muted small">Nombre del Producto *</label>
                        <input type="text" class="form-control bg-light rounded-3" name="nombre" placeholder="Ej. Abono Orgánico 50Kg" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Categoría *</label>
                        <select class="form-select bg-light rounded-3" name="id_categoria" required>
                            <option value="">Selecciona una categoría...</option>
                            <?php foreach($categoriasList as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(empty($categoriasList)): ?>
                            <div class="form-text text-danger mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>Debes crear una categoría primero.</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Unidad de Medida *</label>
                        <input type="text" class="form-control bg-light rounded-3" name="unidad_medida" placeholder="Ej. Kg, Litros, Unidad" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold text-muted small">Descripción del Producto</label>
                        <textarea class="form-control bg-light rounded-3" name="descripcion" rows="3" placeholder="Detalles, usos, composición..."></textarea>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label fw-semibold text-muted small">Imagen del Producto (Opcional)</label>
                        <input type="file" class="form-control bg-light rounded-3" name="imagen" accept="image/*">
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-4 border-bottom pb-3 mt-5"><i class="fa-solid fa-money-bill-wave text-success me-2"></i>Precios e Inventario</h5>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Precio de Compra (Costo) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">$</span>
                            <input type="number" step="0.01" min="0" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="precio_compra" value="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Precio de Venta al Público *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-success fw-bold">$</span>
                            <input type="number" step="0.01" min="0" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="precio_venta" value="0.00" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Stock Inicial *</label>
                        <input type="number" step="0.01" min="0" class="form-control bg-light rounded-3" name="stock_actual" value="0.00" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Stock Mínimo (Para alertas) *</label>
                        <input type="number" step="0.01" min="0" class="form-control bg-light rounded-3" name="stock_minimo" value="5.00" required>
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end gap-3 border-top pt-4">
                    <button type="reset" class="btn btn-light border px-4 fw-medium text-muted rounded-3">Limpiar Formulario</button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold rounded-3 shadow-sm" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                        <i class="fa-solid fa-save me-2"></i>Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
