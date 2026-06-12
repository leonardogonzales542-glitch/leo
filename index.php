<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Página de Presentación Principal (Index)
 * 
 * Este archivo sirve como el panel principal (dashboard) de presentación del sistema.
 * Utiliza arreglos simulados en PHP estructurados para facilitar la posterior conexión a una Base de Datos.
 */

// 1. Simulación de Datos de la Base de Datos (Mocks)
$resumenMetricas = [
    'total_productos' => 148,
    'alertas_stock'   => 4,
    'ventas_hoy'      => 1250000, // En pesos o moneda local
    'clientes_activos'=> 32
];

$alertasStock = [
    [
        'codigo' => 'PUR-DOG-ADU-15',
        'marca' => 'Dog Chow',
        'descripcion' => 'Adultos Medianos y Grandes - Sabor Carne',
        'peso' => '15 Kg',
        'stock_actual' => 3,
        'stock_minimo' => 10,
        'precio_venta' => 45000,
        'estado' => 'Critico'
    ],
    [
        'codigo' => 'PUR-PRO-PUP-03',
        'marca' => 'Pro Plan',
        'descripcion' => 'Puppy Razas Pequeñas - Desarrollo Óptimo',
        'peso' => '3 Kg',
        'stock_actual' => 0,
        'stock_minimo' => 5,
        'precio_venta' => 18000,
        'estado' => 'Agotado'
    ],
    [
        'codigo' => 'PUR-EXC-SEN-12',
        'marca' => 'Excellent',
        'descripcion' => 'Adulto Sensitive - Cuidado Especial Piel',
        'peso' => '12 Kg',
        'stock_actual' => 4,
        'stock_minimo' => 8,
        'precio_venta' => 38000,
        'estado' => 'Critico'
    ],
    [
        'codigo' => 'PUR-DOG-CACH-22',
        'marca' => 'Dog Chow',
        'descripcion' => 'Cachorros Minis y Pequeños - Vida Sana',
        'peso' => '22.7 Kg',
        'stock_actual' => 2,
        'stock_minimo' => 6,
        'precio_venta' => 62000,
        'estado' => 'Critico'
    ]
];

$ventasRecientes = [
    [
        'id' => 'VTA-00104',
        'fecha' => 'Hoy, 11:30 AM',
        'cliente' => 'Veterinaria San Francisco',
        'productos' => '2x Dog Chow Adulto 15Kg, 1x Pro Plan Adulto 15Kg',
        'total' => 178000,
        'metodo' => 'Transferencia'
    ],
    [
        'id' => 'VTA-00103',
        'fecha' => 'Hoy, 09:15 AM',
        'cliente' => 'María Clara Restrepo',
        'productos' => '1x Excellent Cachorro 3Kg',
        'total' => 22000,
        'metodo' => 'Efectivo'
    ],
    [
        'id' => 'VTA-00102',
        'fecha' => 'Ayer, 05:40 PM',
        'cliente' => 'Pet Shop Huellitas',
        'productos' => '5x Pro Plan Puppy Large Breed 15Kg',
        'total' => 485000,
        'metodo' => 'Crédito'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PurinaStock - Sistema de Control de Inventario y Ventas</title>
    
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <!-- 1. BARRA DE NAVEGACIÓN -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center text-white" href="index.php">
                <i class="bi bi-dog-fill text-warning me-2 fs-3"></i>
                <span class="fw-bold">Purina</span><span class="text-warning fw-light">Stock</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" aria-current="page" href="index.php">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/inventario.php">
                            <i class="bi bi-box-seam me-1"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/ventas.php">
                            <i class="bi bi-cart3 me-1"></i> Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/clientes.php">
                            <i class="bi bi-people me-1"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/reportes.php">
                            <i class="bi bi-graph-up-arrow me-1"></i> Reportes
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center text-white">
                    <div class="text-end me-3 d-none d-sm-block">
                        <span class="d-block fw-semibold text-white fs-7">Leonardo González</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.7rem;">Administrador</span>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&h=100&q=80" alt="avatar" width="40" height="40" class="rounded-circle border border-2 border-warning">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownUser1" style="border-radius: 0.75rem;">
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="bi-gear me-2"></i> Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. HERO / BIENVENIDA -->
    <header class="hero-section">
        <div class="container-fluid px-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">Panel Administrativo de Stock y Ventas</h1>
                    <p class="lead text-white-50 mb-0">Control total y monitoreo de existencias de alimento y purinas para perros de alta calidad.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div id="system-time" class="bg-white bg-opacity-10 text-white border border-white border-opacity-10 px-3 py-2 rounded-pill d-inline-block">
                        <span class="status-dot"></span> Cargando estado...
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. CONTENIDO PRINCIPAL -->
    <main class="container-fluid px-5 pb-5">
        
        <!-- Tarjetas de Métricas -->
        <section class="row g-4 mb-5">
            <!-- Total Productos -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Productos en Catálogo</span>
                            <h2 class="fw-bold mt-1 mb-0"><?php echo number_format($resumenMetricas['total_productos']); ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <span class="text-success"><i class="bi bi-arrow-up-short"></i> +12%</span> respecto al mes anterior
                    </div>
                </div>
            </div>
            
            <!-- Alertas de Stock -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-danger">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Alertas de Stock</span>
                            <h2 class="fw-bold mt-1 mb-0 text-danger"><?php echo number_format($resumenMetricas['alertas_stock']); ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-danger">
                        <i class="bi bi-arrow-down-short"></i> Requiere reabastecimiento urgente
                    </div>
                </div>
            </div>

            <!-- Ventas de Hoy -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-success">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Ventas de Hoy</span>
                            <h2 class="fw-bold mt-1 mb-0 text-success">$<?php echo number_format($resumenMetricas['ventas_hoy'] / 1000, 1) . 'k'; ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <span class="text-success"><i class="bi bi-arrow-up-short"></i> +5.4%</span> en las últimas 24 horas
                    </div>
                </div>
            </div>

            <!-- Clientes Activos -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Clientes Activos</span>
                            <h2 class="fw-bold mt-1 mb-0"><?php echo number_format($resumenMetricas['clientes_activos']); ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <span class="text-success"><i class="bi bi-plus-short"></i> +3 nuevos</span> registrados esta semana
                    </div>
                </div>
            </div>
        </section>

        <!-- Accesos Directos (Acciones Rápidas) -->
        <section class="mb-5">
            <h4 class="fw-bold mb-4 d-flex align-items-center">
                <i class="bi bi-lightning-charge-fill text-warning me-2"></i> Acciones Rápidas del Sistema
            </h4>
            <div class="row g-4">
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Controladores/NuevaVenta.php" class="action-btn-card">
                        <i class="bi bi-cart-plus"></i>
                        <h6 class="fw-bold mb-1">Nueva Venta</h6>
                        <p class="text-muted small mb-0">Registrar salida de stock</p>
                    </a>
                </div>
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Vistas/inventario.php?action=nuevo" class="action-btn-card">
                        <i class="bi bi-plus-circle"></i>
                        <h6 class="fw-bold mb-1">Agregar Purina</h6>
                        <p class="text-muted small mb-0">Ingresar nuevo producto</p>
                    </a>
                </div>
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Vistas/reportes.php?view=stock" class="action-btn-card">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <h6 class="fw-bold mb-1">Generar Reporte</h6>
                        <p class="text-muted small mb-0">Exportar stock actual</p>
                    </a>
                </div>
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Vistas/clientes.php?action=nuevo" class="action-btn-card">
                        <i class="bi bi-person-plus"></i>
                        <h6 class="fw-bold mb-1">Registrar Cliente</h6>
                        <p class="text-muted small mb-0">Crear perfil de comprador</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Alertas de Stock Bajo y Transacciones Recientes -->
        <section class="row g-4">
            
            <!-- Col 1: Tabla de Alertas de Stock Bajo -->
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1 text-danger">
                                <i class="bi bi-shield-fill-exclamation me-1"></i> Alertas de Stock Crítico
                            </h5>
                            <p class="text-muted small mb-0">Alimentos y purinas con existencias por debajo del límite mínimo</p>
                        </div>
                        <a href="Vistas/inventario.php?filter=critical" class="btn btn-sm btn-outline-danger rounded-pill px-3">Ver Todo</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th class="text-center">S. Mínimo</th>
                                        <th class="text-center">S. Actual</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alertasStock as $producto): ?>
                                    <tr>
                                        <td class="fw-semibold text-muted" style="font-size: 0.85rem;"><?php echo $producto['codigo']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning-subtle text-warning rounded-3 p-2 me-3 fs-5">
                                                    <i class="bi bi-bag-fill"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold mb-0 text-dark"><?php echo $producto['marca']; ?></span>
                                                    <span class="text-muted small"><?php echo $producto['descripcion'] . ' (' . $producto['peso'] . ')'; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-semibold text-muted"><?php echo $producto['stock_minimo']; ?> unds</td>
                                        <td class="text-center">
                                            <span class="fw-bold text-danger fs-6"><?php echo $producto['stock_actual']; ?></span> <span class="small text-muted">unds</span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($producto['stock_actual'] === 0): ?>
                                                <span class="badge rounded-pill badge-out-of-stock px-3 py-2">Agotado</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill badge-low-stock px-3 py-2">Crítico</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Historial de Ventas Recientes -->
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">
                                <i class="bi bi-receipt-cutoff text-success me-1"></i> Ventas Recientes
                            </h5>
                            <p class="text-muted small mb-0">Últimos movimientos facturados en el sistema</p>
                        </div>
                        <a href="Vistas/ventas.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Ver Historial</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($ventasRecientes as $venta): ?>
                            <div class="list-group-item px-4 py-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="badge bg-light text-dark fw-bold border mb-2" style="font-size: 0.75rem;"><?php echo $venta['id']; ?></span>
                                        <span class="text-muted small ms-2"><i class="bi bi-clock me-1"></i><?php echo $venta['fecha']; ?></span>
                                    </div>
                                    <span class="fw-bold text-success fs-5">$<?php echo number_format($venta['total']); ?></span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-person me-1 text-muted"></i><?php echo $venta['cliente']; ?></h6>
                                <p class="text-muted small mb-0 text-truncate"><i class="bi bi-basket me-1 text-muted"></i><?php echo $venta['productos']; ?></p>
                                <div class="mt-2">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.7rem;"><?php echo $venta['metodo']; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <!-- 4. PIE DE PÁGINA -->
    <footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary border-opacity-20">
        <div class="container-fluid px-5">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2026 <span class="text-white fw-bold">PurinaStock</span>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle">v1.0.0 (Estable)</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Main JS -->
    <script src="public/js/main.js"></script>
</body>
</html>
