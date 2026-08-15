<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/producto.php';
require_once __DIR__ . '/../../models/categoria.php';

$productoModel = new Producto($conn);
$categoriaModel = new Categoria($conn);

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';

if ($filtro == 'stock_bajo') {
    $productos = $productoModel->getBajoStock();
    $tituloPagina = "Stock Bajo";
} else {
    $productos = $productoModel->getAll();
    $tituloPagina = "Inventario Completo";
}

$categorias = $categoriaModel->getAll();
$categoriasList = [];
if ($categorias && $categorias->num_rows > 0) {
    while($c = $categorias->fetch_assoc()) {
        $categoriasList[] = $c;
    }
}

$titulo = 'Gestión de Inventario';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Inventario</h3>
            <span class="text-muted small"><?= $tituloPagina ?></span>
        </div>
        <div class="d-flex gap-2">
            <a href="inventario.php<?= $filtro == 'stock_bajo' ? '' : '?filtro=stock_bajo' ?>" class="btn <?= $filtro == 'stock_bajo' ? 'btn-danger' : 'btn-outline-danger bg-white' ?> rounded-3 shadow-sm px-3 fw-medium">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $filtro == 'stock_bajo' ? 'Ver Todos' : 'Ver Stock Bajo' ?>
            </a>
            <a href="agregar_producto.php" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-plus me-2"></i>Nuevo Producto
            </a>
        </div>
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
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CÓDIGO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">PRODUCTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CATEGORÍA</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">PRECIO V.</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">STOCK</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.8rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($productos && $productos->num_rows > 0): ?>
                        <?php while($p = $productos->fetch_assoc()): ?>
                            <?php 
                                $stockStatusClass = 'success';
                                if ($p['stock_actual'] <= 0) $stockStatusClass = 'danger';
                                elseif ($p['stock_actual'] <= $p['stock_minimo']) $stockStatusClass = 'warning';
                            ?>
                            <tr class="border-bottom">
                                <td class="px-4 py-3 fw-bold text-dark" style="font-size: 0.85rem;"><?= htmlspecialchars($p['codigo']) ?></td>
                                <td class="px-4 py-3 fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($p['imagen']) && $p['imagen'] != 'default.png'): ?>
                                            <img src="../../public/img/productos/<?= htmlspecialchars($p['imagen']) ?>" alt="Img" width="40" height="40" class="rounded-3 object-fit-cover shadow-sm border">
                                        <?php else: ?>
                                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-image text-muted opacity-50"></i>
                                            </div>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted small"><?= htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría') ?></td>
                                <td class="px-4 py-3 fw-bold text-success">$<?= number_format($p['precio_venta'], 2, ',', '.') ?></td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-<?= $stockStatusClass ?> bg-opacity-10 text-<?= $stockStatusClass ?> border border-<?= $stockStatusClass ?> border-opacity-25 rounded-pill px-2 py-1">
                                        <?= floatval($p['stock_actual']) ?> <?= htmlspecialchars($p['unidad_medida']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($p['estado']): ?>
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                    <?php else: ?>
                                        <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button type="button" class="btn btn-sm btn-light border text-primary me-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $p['id_producto'] ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="../../controllers/ProductoController.php?action=delete&id=<?= $p['id_producto'] ?>" class="btn btn-sm btn-light border text-danger shadow-sm rounded-3" onclick="return confirm('¿Eliminar producto <?= htmlspecialchars($p['nombre']) ?>?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEdit<?= $p['id_producto'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom bg-light rounded-top-4">
                                            <h5 class="modal-title fw-bold">Editar Producto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="../../controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                                                
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold text-muted small">Código</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="codigo" value="<?= htmlspecialchars($p['codigo']) ?>" required>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label fw-semibold text-muted small">Nombre del Producto</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>" required>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Categoría</label>
                                                        <select class="form-select bg-light rounded-3" name="id_categoria" required>
                                                            <?php foreach($categoriasList as $cat): ?>
                                                                <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $p['id_categoria'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Unidad de Medida (ej. Kg, Lts, Und)</label>
                                                        <input type="text" class="form-control bg-light rounded-3" name="unidad_medida" value="<?= htmlspecialchars($p['unidad_medida']) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Precio de Compra</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">$</span>
                                                            <input type="number" step="0.01" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="precio_compra" value="<?= htmlspecialchars($p['precio_compra']) ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Precio de Venta</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0 text-success fw-bold">$</span>
                                                            <input type="number" step="0.01" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="precio_venta" value="<?= htmlspecialchars($p['precio_venta']) ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Stock Actual</label>
                                                        <input type="number" step="0.01" class="form-control bg-light rounded-3" name="stock_actual" value="<?= htmlspecialchars($p['stock_actual']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">Stock Mínimo (Alerta)</label>
                                                        <input type="number" step="0.01" class="form-control bg-light rounded-3" name="stock_minimo" value="<?= htmlspecialchars($p['stock_minimo']) ?>" required>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold text-muted small">Descripción</label>
                                                        <textarea class="form-control bg-light rounded-3" name="descripcion" rows="2"><?= htmlspecialchars($p['descripcion']) ?></textarea>
                                                    </div>
                                                    
                                                    <div class="col-12 mt-3">
                                                        <label class="form-label fw-semibold text-muted small">Imagen del Producto (Opcional)</label>
                                                        <input type="file" class="form-control bg-light rounded-3" name="imagen" accept="image/*">
                                                        <?php if (!empty($p['imagen']) && $p['imagen'] != 'default.png'): ?>
                                                            <div class="mt-2 text-muted small">
                                                                <i class="fa-solid fa-check text-success me-1"></i> Imagen actual: <?= htmlspecialchars($p['imagen']) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="col-12 mt-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado<?= $p['id_producto'] ?>" <?= $p['estado'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label fw-semibold text-dark" for="estado<?= $p['id_producto'] ?>">Producto Activo para la Venta</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light rounded-bottom-4">
                                                <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Actualizar Producto</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fs-1 opacity-25 mb-3 d-block"></i>
                                No hay productos en el inventario. <a href="agregar_producto.php">Agrega uno nuevo</a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
