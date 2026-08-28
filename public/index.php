<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComerSua - Control Total de tus Insumos</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23cd5219'%3E%3Cpath d='M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z'/%3E%3C/svg%3E">
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom Style Sheet -->
    <link href="css/comersua.css" rel="stylesheet">
</head>
<body class="landing-comersua">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fa-solid fa-basket-shopping me-2"></i>ComerSua
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Categorías</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Módulos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">¿Cómo funciona?</a></li>
                </ul>
                
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <div class="nav-status-badge">
                        <i class="fa-solid fa-server me-2"></i> Conectado a MySQL
                    </div>
                    <a href="../views/auth/login.php" class="btn btn-cs-primary shadow-sm px-4">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Acceder
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                
                <!-- Left Side: Content -->
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="cat-pill"><i class="fa-solid fa-droplet text-danger"></i> Aceites</span>
                        <span class="cat-pill"><i class="fa-brands fa-pagelines text-warning"></i> Harinas</span>
                        <span class="cat-pill"><i class="fa-solid fa-bottle-droplet text-primary"></i> Lácteos</span>
                        <span class="cat-pill"><i class="fa-solid fa-pepper-hot text-danger"></i> Especias</span>
                        <span class="cat-pill"><i class="fa-solid fa-box-open text-secondary"></i> Enlatados</span>
                    </div>
                    
                    <h1>
                        Control Total de tus <br>
                        <span class="text-highlight">Insumos Alimentarios</span>
                    </h1>
                    
                    <p class="hero-subtitle">
                        Trazabilidad completa de lotes por categoría de alimento.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="../views/auth/login.php" class="btn btn-cs-primary">
                            Iniciar Sesión &rarr;
                        </a>
                        <a href="#" class="btn btn-cs-outline">
                            Ver Módulos
                        </a>
                    </div>
                    
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="stat-item">
                            <div class="stat-num">+200</div>
                            <div class="stat-text">Insumos en catálogo</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-num">100%</div>
                            <div class="stat-text">Trazabilidad de lotes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-num">0</div>
                            <div class="stat-text">Stock negativo</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side: Widget -->
                <div class="col-lg-6">
                    <div class="widget-card ms-lg-4">
                        
                        <div class="widget-header">
                            <h3 class="widget-title">
                                <i class="fa-solid fa-cart-flatbed" style="color: var(--cs-primary);"></i> 
                                Registro rápido de venta
                            </h3>
                            <div class="widget-alert-badge">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> 3 Próximos a vencer
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="cs-label">Buscar insumo alimentario</label>
                            <div class="position-relative">
                                <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" class="form-control form-control-cs w-100 ps-5" value="Harina de trigo 50kg" readonly>
                            </div>
                        </div>
                        
                        <div class="product-sel-card">
                            <div class="product-sel-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Harina de trigo 50kg</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.7rem;">Lote: HT-2026-08 &nbsp;&bull;&nbsp; Vence: 15/02/2027</p>
                            </div>
                            <div>
                                <span class="stock-badge">Stock: 240 u.</span>
                            </div>
                        </div>
                        
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-8">
                                <label class="cs-label">Precio unitario</label>
                                <div class="d-flex align-items-baseline gap-1">
                                    <h2 class="fw-bold mb-0 text-dark" style="font-family: var(--cs-font-title); letter-spacing: -0.5px;">$85.000</h2>
                                    <span class="text-muted" style="font-size: 0.85rem;">/saco</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="cs-label">Cantidad</label>
                                <input type="number" class="form-control form-control-cs text-center fw-bold" value="1" readonly>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-cs-primary w-100 py-3 d-flex justify-content-center align-items-center gap-2" onclick="window.location.href='../views/auth/login.php'">
                            Procesar Venta <i class="fa-regular fa-circle-check"></i>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Bottom Bar (Categories) -->
    <div class="bottom-bar">
        <div class="container-fluid px-4">
            <div class="d-flex flex-nowrap align-items-center justify-content-center">
                <a href="#" class="bottom-link">...y Derivados</a>
                <a href="#" class="bottom-link">Especias y Condimentos</a>
                <a href="#" class="bottom-link">Azúcares y Endulzantes</a>
                <a href="#" class="bottom-link">Enlatados y Conservas</a>
                <a href="#" class="bottom-link">Proteínas y Embutidos</a>
                <a href="#" class="bottom-link">Bebidas e Infusiones</a>
                <a href="#" class="bottom-link">Salsas y Aderezos</a>
                <a href="#" class="bottom-link">Pastas y Arroces</a>
            </div>
        </div>
    </div>

</body>
</html>
