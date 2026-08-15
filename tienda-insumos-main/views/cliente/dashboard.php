<?php
$titulo = 'Mi Panel | AgriStock';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto($conn);
$productosCatalogo = $productoModel->getAll();
?>

<!-- Sidebar del Cliente -->
<div class="bg-dark text-white p-3 d-flex flex-column" style="width: 260px; transition: all 0.3s;">
    <div class="text-center mb-4 mt-2">
        <h3 class="text-success fw-bold m-0"><i class="fas fa-leaf me-2"></i>AgriStock</h3>
        <small class="text-muted">Panel de Cliente</small>
    </div>
    <hr class="text-secondary mt-0">
    <ul class="nav flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-white bg-success rounded-3 shadow-sm"><i class="fas fa-home me-3 w-20px"></i>Inicio</a>
        </li>
        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-light hover-bg-light rounded-3" style="opacity: 0.8;"><i class="fas fa-shopping-basket me-3"></i>Mis Pedidos</a>
        </li>
        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-light hover-bg-light rounded-3" style="opacity: 0.8;"><i class="fas fa-heart me-3"></i>Favoritos</a>
        </li>
        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-light hover-bg-light rounded-3" style="opacity: 0.8;"><i class="fas fa-user-circle me-3"></i>Mi Perfil</a>
        </li>
    </ul>
    <hr class="text-secondary">
    <a href="../../controllers/auth/authController.php?action=logout" class="nav-link text-danger fw-semibold d-flex align-items-center">
        <i class="fas fa-sign-out-alt me-3"></i> Cerrar Sesión
    </a>
</div>

<!-- Contenido Principal -->
<main class="flex-grow-1 bg-light overflow-auto">
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4 py-3 sticky-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <span class="fs-4 fw-semibold text-dark">Bienvenido de nuevo, <?= htmlspecialchars($_SESSION['usuario']['usuario'] ?? 'Cliente') ?> 👋</span>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-light rounded-circle p-2 me-3 position-relative">
                    <i class="fas fa-bell text-secondary"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['usuario']['usuario'] ?? 'C') ?>&background=10b981&color=fff&rounded=true" alt="Perfil" width="40" height="40" class="rounded-circle shadow-sm">
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container-fluid p-4">
        <!-- Tarjetas de Resumen -->
        <div class="row g-4 mb-4">
            <!-- Card 1 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift" style="transition: transform 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">Mis Pedidos</h6>
                                <h2 class="fw-bold text-dark mb-0">0</h2>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fas fa-shopping-bag fa-lg"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0"><i class="fas fa-arrow-right text-success me-1"></i> Ir a la tienda</p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift" style="transition: transform 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">Gastos del Mes</h6>
                                <h2 class="fw-bold text-dark mb-0">$0.00</h2>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Mantén el control de tus compras</p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift" style="transition: transform 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">Puntos AgriStock</h6>
                                <h2 class="fw-bold text-dark mb-0">0 pts</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fas fa-star fa-lg"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Acumula puntos con cada compra</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Catálogo de Productos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark mb-0"><i class="fas fa-store text-success me-2"></i>Catálogo de Productos Purina</h4>
                </div>
                
                <div class="row g-4">
                    <?php if ($productosCatalogo && $productosCatalogo->num_rows > 0): ?>
                        <?php while($p = $productosCatalogo->fetch_assoc()): ?>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden">
                                    <?php 
                                        $imgPath = !empty($p['imagen']) && $p['imagen'] != 'default.png' 
                                            ? '../../public/img/productos/' . htmlspecialchars($p['imagen']) 
                                            : 'https://images.pexels.com/photos/1564473/pexels-photo-1564473.jpeg?auto=compress&cs=tinysrgb&w=400'; 
                                    ?>
                                    <div class="position-relative" style="height: 200px; background-color: #f8f9fa;">
                                        <img src="<?= $imgPath ?>" class="w-100 h-100 object-fit-contain p-3" alt="<?= htmlspecialchars($p['nombre']) ?>">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1 fs-6">
                                                $<?= number_format($p['precio_venta'], 2, ',', '.') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body p-4 d-flex flex-column">
                                        <small class="text-muted fw-semibold mb-1"><?= htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría') ?></small>
                                        <h6 class="fw-bold text-dark mb-2"><?= htmlspecialchars($p['nombre']) ?></h6>
                                        <p class="text-secondary small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= htmlspecialchars($p['descripcion']) ?>
                                        </p>
                                        <button class="btn btn-outline-success w-100 rounded-pill fw-medium btn-sm mt-auto p-2">
                                            <i class="fas fa-cart-plus me-2"></i>Añadir al Carrito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body p-5 text-center">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-box-open fa-2x text-muted"></i>
                                    </div>
                                    <h6 class="fw-semibold text-dark">No hay productos disponibles por el momento.</h6>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Soporte Técnico -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
                        <div class="d-flex align-items-center gap-4 mb-3 mb-md-0">
                            <i class="fas fa-headset fa-3x opacity-75"></i>
                            <div>
                                <h4 class="fw-bold mb-1">¿Necesitas asesoría con tus compras?</h4>
                                <p class="mb-0 opacity-75">Nuestro equipo está disponible 24/7 para ayudarte a elegir los mejores productos.</p>
                            </div>
                        </div>
                        <button class="btn btn-light text-success fw-bold rounded-pill px-4 py-2 shadow-sm text-nowrap">Contactar Soporte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>