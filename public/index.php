<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AgriStock Purina - Gestión de Alimentos para Mascotas</title>
  <link rel="stylesheet" href="css/comersua.css?v=<?php echo time(); ?>">
</head>
<body>

    <header>
      <div class="logo">
        <img src="https://cdn-icons-png.flaticon.com/512/3233/3233483.png" alt="Logo">
        AgriStock
      </div>
      <nav>
        <a href="#" class="active">Inicio</a>
        <a href="#">Nosotros</a>
        <a href="#">Productos</a>
        <a href="#">Contacto</a>
      </nav>
      <div class="header-actions">
        <a href="../views/auth/login.php" class="btn-nav-login">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>
          Iniciar Sesión
        </a>
      </div>
    </header>

    <div class="hero-container">
      <div class="hero-left">
        <span class="tag-pill">🐶 Nutrición premium y trazabilidad</span>
        
        <h1 class="hero-title">
          AgriStock
          <span>Purina</span>
        </h1>
        
        <p class="hero-subtitle">
          Gestión de inventario de purina y alimento para mascotas con trazabilidad de lotes, control de proveedores, fechas de vencimiento y ventas en una sola plataforma.
        </p>

        <div class="hero-buttons">
          <a href="../views/auth/login.php" class="btn-primary">
            🚀 Acceso al Sistema
          </a>
          <a href="#" class="btn-secondary">
            ↓ Conocer Más
          </a>
        </div>

        <div class="features-list">
          <span>+150 Marcas de Purina</span>
          <span>Lotes Trazables</span>
          <span>Alertas de Vencimiento</span>
        </div>
      </div>

      <div class="hero-right">
        <!-- Imagen de fondo decorativa para el panel (reemplazada con nuestra IA) -->
        <img src="img/hero-dog.jpg" alt="Fondo" class="bg-image">
        
        <div class="glass-panel">
          <div class="panel-header">
            <div class="panel-title">
              <svg width="20" height="20" fill="var(--primary)" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              Panel Operativo
            </div>
            <span class="status-indicator">Online</span>
          </div>

          <div class="stats-grid">
            <div class="stat-box">
              <div class="stat-label">Stock Activo</div>
              <div class="stat-value">95%</div>
              <div class="stat-subtext">Capacidad</div>
            </div>
            <div class="stat-box">
              <div class="stat-label">Ventas Hoy</div>
              <div class="stat-value">120</div>
              <div class="stat-subtext">Unidades</div>
            </div>
            <div class="stat-box">
              <div class="stat-label">Rotación</div>
              <div class="stat-value">Alta</div>
              <div class="stat-subtext">Demanda</div>
            </div>
            <div class="stat-box">
              <div class="stat-label">Lotes</div>
              <div class="stat-value">340</div>
              <div class="stat-subtext">Trazables</div>
            </div>
          </div>
        </div>
      </div>
    </div>

</body>
</html>
