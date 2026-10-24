<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AgriStock Purina - Gestión de Alimentos para Mascotas</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#0b1020">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="apple-touch-icon" href="/icons/icon-192.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/comersua.css') }}">
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(err => {
          console.log('SW registration failed:', err);
        });
      });
    }
  </script>
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
        <a href="/login" class="btn-nav-login">
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
          <a href="/login" class="btn-primary">
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
        
        <div style="margin-top: 30px; font-size: 0.95rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" fill="var(--primary)" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
            </svg>
            <strong>Ubicación:</strong> Calle 3 #7-22, Yaguará, Colombia
        </div>
      </div>

      <div class="hero-right">
        <!-- Imagen de fondo decorativa para el panel -->
        <img src="{{ asset('img/hero-dog.jpg') }}" alt="Fondo" class="bg-image">
        
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

    <!-- PIE DE PÁGINA (FOOTER) -->
    <footer class="landing-footer">
      <div class="footer-main-grid">
        <!-- Columna 1: Marca & Descripción -->
        <div class="footer-col-brand">
          <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/3233/3233483.png" alt="AgriStock Logo">
            AgriStock <span style="font-style: italic; color: #a4d2ba; font-weight: 600;">Purina</span>
          </div>
          <p class="footer-brand-desc">
            Líderes en nutrición animal y distribución de purinas de alta calidad. Trazabilidad de lotes, control de vencimiento y abastecimiento confiable para el bienestar de tus animales.
          </p>
          <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div class="footer-status-tag">
              <span class="pulse-circle"></span>
              Plataforma Online • 100% Operativa
            </div>
            <span style="font-size: 0.8rem; color: var(--text-muted); border: 1px solid var(--glass-border); padding: 4px 10px; border-radius: 20px;">
              <i class="fa-solid fa-shield-halved" style="color: var(--primary); margin-right: 4px;"></i> Garantía Certificada
            </span>
          </div>
        </div>

        <!-- Columna 2: Navegación Rápida -->
        <div>
          <h4 class="footer-col-title">Navegación</h4>
          <ul class="footer-nav-list">
            <li><a href="#"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; color: var(--primary);"></i> Inicio</a></li>
            <li><a href="#nosotros"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; color: var(--primary);"></i> Sobre Nosotros</a></li>
            <li><a href="#productos"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; color: var(--primary);"></i> Líneas de Purina</a></li>
            <li><a href="#contacto"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; color: var(--primary);"></i> Contacto Directo</a></li>
            <li><a href="/login"><i class="fa-solid fa-arrow-right-to-bracket" style="font-size: 0.8rem; color: var(--primary);"></i> Iniciar Sesión</a></li>
          </ul>
        </div>

        <!-- Columna 3: Contacto, Ubicación & WhatsApp -->
        <div>
          <h4 class="footer-col-title">Contacto & Ubicación</h4>
          <ul class="footer-contact-list">
            <li class="footer-contact-item">
              <i class="fa-solid fa-location-dot"></i>
              <div>
                <strong style="color: #fff; display: block;">Sede Principal</strong>
                Calle 3 #7-22, Yaguará, Huila, Colombia
              </div>
            </li>
            <li class="footer-contact-item">
              <i class="fa-brands fa-whatsapp" style="color: #25d366;"></i>
              <div>
                <strong style="color: #fff; display: block;">Línea WhatsApp</strong>
                <a href="https://wa.me/573222673734?text=Hola%20AgriStock,%20deseo%20hacer%20un%20pedido%20o%20consulta" target="_blank" style="color: #a4d2ba; text-decoration: none; font-weight: 600;">
                  +57 322 267 3734
                </a>
              </div>
            </li>
            <li class="footer-contact-item">
              <i class="fa-solid fa-clock" style="color: var(--primary);"></i>
              <div>
                <strong style="color: #fff; display: block;">Horario de Atención</strong>
                Lunes a Sábado: 7:00 AM – 6:00 PM
              </div>
            </li>
          </ul>

          <a href="https://wa.me/573222673734?text=Hola%20AgriStock,%20deseo%20hacer%20un%20pedido" target="_blank" class="footer-wa-btn">
            <i class="fa-brands fa-whatsapp" style="font-size: 1.2rem;"></i> Chatear por WhatsApp
          </a>
        </div>
      </div>

      <!-- Barra Inferior Legal -->
      <div class="footer-bottom-bar">
        <div>
          &copy; {{ date('Y') }} <strong>AgriStock</strong> Purina. Todos los derechos reservados.
        </div>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
          <span>Nutrición Animal de Precisión</span>
          <span style="opacity: 0.3;">•</span>
          <a href="#" style="color: var(--text-muted); text-decoration: none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-muted)'">Políticas de Privacidad</a>
          <span style="opacity: 0.3;">•</span>
          <a href="#" style="color: var(--text-muted); text-decoration: none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-muted)'">Términos de Servicio</a>
        </div>
      </div>
    </footer>
</body>
</html>
