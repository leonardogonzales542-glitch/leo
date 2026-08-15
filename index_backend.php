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

$meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
$fechaActivo = date('j') . ' ' . $meses[date('n') - 1] . ' ' . date('Y') . ' - ' . date('H:i:s');
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

    <!-- NAVEGACIÓN PRINCIPAL -->
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
                        <a class="nav-link nav-link-custom active" aria-current="page" href="index.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/inventario.php"><i class="bi bi-box-seam me-1"></i> Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/ventas.php"><i class="bi bi-cart3 me-1"></i> Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/clientes.php"><i class="bi bi-people me-1"></i> Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="Vistas/reportes.php"><i class="bi bi-graph-up-arrow me-1"></i> Reportes</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <a href="register.php" class="btn btn-outline-light btn-sm px-3" style="border-radius:8px; font-size:.82rem;">
                        <i class="bi bi-person-plus me-1"></i> Registrarse
                    </a>
                    <a href="login.php" class="btn btn-warning btn-sm px-3 text-dark fw-semibold" style="border-radius:8px; font-size:.82rem;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                    </a>
                    <div class="dropdown ms-1">
                        <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&h=100&q=80" alt="avatar" width="38" height="38" class="rounded-circle border border-2 border-warning">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownUser1" style="border-radius: 0.75rem;">
                            <li><span class="dropdown-item-text fw-semibold text-dark">Leonardo González</span></li>
                            <li><span class="dropdown-item-text text-muted small" style="font-size:.75rem;">Administrador</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i> Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="login.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO DE PRESENTACIÓN -->
    <header class="hero-section">
        <div class="container-fluid px-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-warning-subtle text-warning rounded-pill mb-3">Sistema de inventario y ventas</span>
                    <h1 class="display-5 fw-bold mb-3">Panel Administrativo de Stock y Ventas</h1>
                    <p class="lead text-white-50 mb-4">Control total y monitoreo de existencias de alimento y purinas para perros de alta calidad.</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="status-pill bg-white bg-opacity-15 border border-white border-opacity-25 text-white rounded-pill d-inline-flex align-items-center px-4 py-2">
                        <span class="status-dot"></span>
                        <span>Sistema Activo: <?php echo $fechaActivo; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container-fluid px-5 pb-5">
        
        <section class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Productos</span>
                            <h2 class="fw-bold mt-1 mb-0"><?php echo number_format($resumenMetricas['total_productos']); ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">Base de datos optimizada para purinas y alimentos caninos.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-danger">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Alertas</span>
                            <h2 class="fw-bold mt-1 mb-0 text-danger"><?php echo number_format($resumenMetricas['alertas_stock']); ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-danger">Vigilancia continua de los productos en riesgo de agotarse.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-success">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Ventas hoy</span>
                            <h2 class="fw-bold mt-1 mb-0 text-success">$<?php echo number_format($resumenMetricas['ventas_hoy'] / 1000, 1) . 'k'; ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">Datos en tiempo real para decisiones más rápidas.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="metric-card metric-info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase text-muted fw-semibold small">Clientes</span>
                            <h2 class="fw-bold mt-1 mb-0"><?php echo number_format($resumenMetricas['clientes_activos']); ?></h2>
                        </div>
                        <div class="metric-icon-wrapper">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">Gestión de clientes enfocada en fidelizar compradores de purina.</div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h4 class="fw-bold mb-4 d-flex align-items-center">
                <i class="bi bi-lightning-charge-fill text-warning me-2"></i> Acciones Rápidas del Sistema
            </h4>
            <div class="row g-4">
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Controladores/NuevaVenta.php" class="action-btn-card action-quick-card">
                        <i class="bi bi-cart-plus"></i>
                        <h6 class="fw-bold mb-1">Nueva Venta</h6>
                        <p class="text-muted small mb-0">Registrar salida de stock</p>
                    </a>
                </div>
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Vistas/inventario.php?action=nuevo" class="action-btn-card action-quick-card">
                        <i class="bi bi-plus-circle"></i>
                        <h6 class="fw-bold mb-1">Agregar Purina</h6>
                        <p class="text-muted small mb-0">Ingresar nuevo producto</p>
                    </a>
                </div>
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Vistas/reportes.php?view=stock" class="action-btn-card action-quick-card">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <h6 class="fw-bold mb-1">Generar Reporte</h6>
                        <p class="text-muted small mb-0">Exportar stock actual</p>
                    </a>
                </div>
                <div class="col-6 col-sm-6 col-md-3">
                    <a href="Vistas/clientes.php?action=nuevo" class="action-btn-card action-quick-card">
                        <i class="bi bi-person-plus"></i>
                        <h6 class="fw-bold mb-1">Registrar Cliente</h6>
                        <p class="text-muted small mb-0">Crear perfil de comprador</p>
                    </a>
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
