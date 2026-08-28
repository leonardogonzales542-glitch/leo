<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriStock - Sistema Profesional de Inventario y Ventas Agrícolas</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310b981'%3E%3Cpath d='M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z'/%3E%3C/svg%3E">
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom Style Sheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3 glass-nav">
        <div class="container">
            <a class="navbar-brand fw-bold text-success fs-3 d-flex align-items-center" href="#">
                <i class="bi bi-patch-check-fill text-success me-2"></i>AgriStock
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link active fw-medium px-3" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 fw-medium px-3" href="#features">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 fw-medium px-3" href="#statistics">Métricas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 fw-medium px-3" href="#about">Nosotros</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="../views/auth/login.php" class="btn btn-premium btn-premium-primary px-4 py-2 rounded-pill fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Acceso al Sistema
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-wrapper text-white position-relative overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="badge-premium mb-3 d-inline-block">
                        <i class="bi bi-star-fill me-1"></i> Líder en Gestión Agropecuaria
                    </span>
                    <h1 class="display-3 fw-bold mb-3 lh-1">
                        Control de Stock y POS para <span class="text-gradient">Insumos Agrícolas</span>
                    </h1>
                    <p class="lead text-secondary mb-4 fs-4">
                        Optimiza tu inventario, agiliza las ventas en mostrador y lleva un control profesional de semillas, fertilizantes y agroquímicos desde una sola plataforma.
                    </p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-lg-start">
                        <a href="#features" class="btn btn-premium btn-premium-primary px-4 py-3 rounded-pill fw-bold shadow">
                            Ver Módulos <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="../views/auth/login.php" class="btn btn-premium btn-premium-outline px-4 py-3 rounded-pill fw-semibold">
                            Demostración En Vivo
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative hover-zoom-img">
                        <img src="https://images.pexels.com/photos/2252482/pexels-photo-2252482.jpeg?auto=compress&cs=tinysrgb&w=800" class="img-fluid rounded-4 shadow-lg border border-secondary border-opacity-25" alt="Control de Cultivos" style="object-fit: cover; width: 100%; max-height: 480px;">
                        <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 m-3 p-3 rounded-3 border border-secondary border-opacity-25 d-none d-sm-flex align-items-center gap-3">
                            <div class="bg-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-lightning-fill text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-white fw-bold small">Actualización en tiempo real</p>
                                <p class="mb-0 text-white-50 small">Sincronización de stock al instante</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Metrics Section -->
    <section id="statistics" class="py-5 bg-white border-bottom">
        <div class="container py-4">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <h2 class="metric-num mb-1">99.9%</h2>
                    <p class="text-muted mb-0 fw-medium">Precisión de Inventario</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="metric-num mb-1">+500k</h2>
                    <p class="text-muted mb-0 fw-medium">Productos Registrados</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="metric-num mb-1">15 min</h2>
                    <p class="text-muted mb-0 fw-medium">Tiempo de Implementación</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="metric-num mb-1">24/7</h2>
                    <p class="text-muted mb-0 fw-medium">Soporte Técnico Especializado</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark display-5">Módulos Especializados</h2>
                <p class="text-muted fs-5">Diseñados con base en las necesidades reales del distribuidor agrícola.</p>
                <hr class="mx-auto bg-success border-success" style="width: 80px; height: 4px; opacity: 1;">
            </div>
            <div class="row g-4">
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4 p-3 bg-white premium-card">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 d-inline-flex mb-3 card-icon-wrapper">
                                <i class="bi bi-box-seam-fill fs-2"></i>
                            </div>
                            <h4 class="fw-bold card-title mb-2">Control de Stock</h4>
                            <p class="card-text text-secondary small">Administración detallada de semillas, herbicidas y abonos. Sistema de trazabilidad por lotes y caducidad.</p>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4 p-3 bg-white premium-card">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 d-inline-flex mb-3 card-icon-wrapper">
                                <i class="bi bi-printer-fill fs-2"></i>
                            </div>
                            <h4 class="fw-bold card-title mb-2">Punto de Venta</h4>
                            <p class="card-text text-secondary small">Facturación rápida, control de caja diario, múltiples métodos de pago y emisión de tickets al instante.</p>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4 p-3 bg-white premium-card">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 d-inline-flex mb-3 card-icon-wrapper">
                                <i class="bi bi-shield-lock-fill fs-2"></i>
                            </div>
                            <h4 class="fw-bold card-title mb-2">Seguridad y Roles</h4>
                            <p class="card-text text-secondary small">Configuración de accesos para vendedores, cajeros y administradores. Registro detallado de movimientos.</p>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4 p-3 bg-white premium-card">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 d-inline-flex mb-3 card-icon-wrapper">
                                <i class="bi bi-bar-chart-line-fill fs-2"></i>
                            </div>
                            <h4 class="fw-bold card-title mb-2">Reportes Financieros</h4>
                            <p class="card-text text-secondary small">Gráficos interactivos de rentabilidad, productos más vendidos y alertas de resurtido automático.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section / Double Column Split -->
    <section id="about" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="position-relative hover-zoom-img">
                        <img src="https://images.pexels.com/photos/974314/pexels-photo-974314.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Tractor Agrícola" class="img-fluid rounded-4 shadow-lg border border-light">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold text-dark display-6 mb-3">Trazabilidad completa en productos delicados</h2>
                    <p class="text-secondary fs-5 mb-4">
                        Los insumos agrícolas requieren regulaciones específicas. Nuestro sistema permite registrar fechas de vencimiento, números de lote del fabricante y clasificaciones de toxicidad.
                    </p>
                    <div class="list-group list-group-flush mb-4">
                        <div class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 premium-list-item">
                            <i class="bi bi-shield-fill-check text-success fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Cumplimiento Normativo</h6>
                                <p class="text-muted small mb-0">Alertas visuales previas al vencimiento de fertilizantes y pesticidas.</p>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 premium-list-item">
                            <i class="bi bi-grid-1x2-fill text-success fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Soporte Multialmacén</h6>
                                <p class="text-muted small mb-0">Controla stock en tienda física, bodegas principales y sucursales.</p>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 premium-list-item">
                            <i class="bi bi-cloud-check-fill text-success fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Respaldo Automático</h6>
                                <p class="text-muted small mb-0">Tus datos de ventas y clientes están seguros en la nube 24/7.</p>
                            </div>
                        </div>
                    </div>
                    <a href="../views/auth/login.php" class="btn btn-premium btn-premium-primary px-4 py-3 rounded-pill fw-bold shadow-sm">
                        Probar Sistema Ahora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials / Social Proof (Extra touch of Senior design) -->
    <section class="py-5 bg-light border-top border-bottom">
        <div class="container py-5 text-center">
            <h2 class="fw-bold text-dark mb-4">Confianza en el Sector Agropecuario</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <i class="bi bi-quote text-success display-1 line-height-1"></i>
                    <p class="fs-4 italic text-secondary mb-4">
                        "Gracias a AgriStock logramos reducir las pérdidas por insumos vencidos a cero. El módulo de punto de venta es sumamente intuitivo y rápido para atender a nuestros agricultores."
                    </p>
                    <h6 class="fw-bold mb-0">Ing. Manuel Lozada</h6>
                    <span class="text-muted small">Gerente de Operaciones, AgroInsumos del Norte</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-success text-white text-center shadow position-relative">
        <div class="container py-5">
            <h2 class="fw-bold display-5 mb-3">Lleva el control de tu agropecuaria al siguiente nivel</h2>
            <p class="lead mb-4 opacity-75">Únete hoy y obtén una demostración guiada sin costo por nuestro equipo técnico.</p>
            <a href="../views/auth/login.php" class="btn btn-light text-success btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                <i class="bi bi-laptop me-2"></i>Iniciar Demostración Gratis
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white-50">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="#" class="text-success fw-bold text-decoration-none fs-3 d-inline-block mb-3">
                        <i class="bi bi-patch-check-fill me-2"></i>AgriStock
                    </a>
                    <p class="small text-secondary">La solución tecnológica definitiva para el control de inventario, proveedores y facturación rápida de insumos agrícolas.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-3">Navegación</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none small">Inicio</a></li>
                        <li class="mb-2"><a href="#features" class="text-secondary text-decoration-none small">Características</a></li>
                        <li class="mb-2"><a href="#statistics" class="text-secondary text-decoration-none small">Métricas de Rendimiento</a></li>
                        <li class="mb-2"><a href="#about" class="text-secondary text-decoration-none small">Sobre Nosotros</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-3">Contacto Directo</h5>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2 text-success"></i> Oficina Principal / Sucursal Física</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2 text-success"></i> soporte@agristock.com</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2 text-success"></i> +1 234 567 890</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-secondary border-opacity-50">
            <div class="text-center small text-secondary">
                <p class="mb-0">&copy; 2026 AgriStock. Todos los derechos reservados. Diseñado bajo estándares Bootstrap 5.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.2 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
