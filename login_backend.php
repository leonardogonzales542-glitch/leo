<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar Sesión – TiendaInsumo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      background: #f3f4f6;
    }

    /* ── PANEL IZQUIERDO ── */
    .left-panel {
      display: none;
      flex: 0 0 440px;
      background: linear-gradient(155deg, #145a27 0%, #1e7a38 45%, #27a34c 100%);
      flex-direction: column;
      justify-content: space-between;
      padding: 52px 46px;
      position: relative;
      overflow: hidden;
      color: #fff;
    }
    @media(min-width: 900px) { .left-panel { display: flex; } }

    .left-panel::before {
      content: ''; position: absolute;
      width: 380px; height: 380px; border-radius: 50%;
      border: 65px solid rgba(255,255,255,.07);
      top: -90px; right: -90px;
    }
    .left-panel::after {
      content: ''; position: absolute;
      width: 280px; height: 280px; border-radius: 50%;
      border: 55px solid rgba(255,255,255,.05);
      bottom: -70px; left: -70px;
    }

    .lp-bg {
      position: absolute; inset: 0;
      background: url('https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=800&q=40') center/cover no-repeat;
      opacity: .07;
    }

    .lp-body { position: relative; z-index: 1; }

    .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 54px; }
    .brand-icon {
      width: 46px; height: 46px;
      background: rgba(255,255,255,.2);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.25rem;
    }
    .brand-name { font-size: 1.3rem; font-weight: 700; }

    .lp-tag {
      font-size: .68rem; font-weight: 600;
      letter-spacing: 2.5px; text-transform: uppercase;
      color: rgba(255,255,255,.55); margin-bottom: 14px;
    }
    .lp-title {
      font-size: 2.6rem; font-weight: 800;
      line-height: 1.1; letter-spacing: -1px; margin-bottom: 18px;
    }
    .lp-desc {
      font-size: .875rem; color: rgba(255,255,255,.72);
      line-height: 1.75; max-width: 310px;
    }

    .info-cards { margin-top: 44px; display: flex; flex-direction: column; gap: 13px; }
    .ic {
      display: flex; align-items: center; gap: 14px;
      background: rgba(255,255,255,.1);
      border-radius: 12px; padding: 14px 18px;
    }
    .ic i { font-size: 1.05rem; color: rgba(255,255,255,.9); width: 20px; text-align: center; }
    .ic span { font-size: .82rem; color: rgba(255,255,255,.8); line-height: 1.4; }

    .lp-footer { position: relative; z-index: 1; font-size: .72rem; color: rgba(255,255,255,.38); }

    /* ── PANEL DERECHO ── */
    .right-panel {
      flex: 1; display: flex;
      align-items: center; justify-content: center;
      padding: 48px 28px; background: #fff;
    }

    .form-box { width: 100%; max-width: 390px; }

    .form-box h2 {
      font-size: 1.9rem; font-weight: 800;
      color: #111827; letter-spacing: -.5px; margin-bottom: 6px;
    }
    .form-box .sub { font-size: .875rem; color: #6b7280; margin-bottom: 30px; }

    /* Alerta */
    .msg-box {
      display: none; padding: 12px 16px; border-radius: 10px;
      font-size: .83rem; margin-bottom: 18px; gap: 8px; align-items: center;
    }
    .msg-box.err { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
    .msg-box.show { display: flex; }

    /* Inputs */
    .fg { margin-bottom: 16px; }

    .iw { position: relative; display: flex; align-items: center; }
    .iw .ico {
      position: absolute; left: 13px;
      color: #9ca3af; font-size: .9rem;
      pointer-events: none; transition: color .2s;
    }
    .iw input {
      width: 100%;
      padding: 13px 14px 13px 40px;
      border: 1.5px solid #e5e7eb; border-radius: 10px;
      font-size: .875rem; font-family: 'Inter', sans-serif;
      color: #111827; background: #f9fafb; outline: none;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .iw input::placeholder { color: #9ca3af; }
    .iw input:focus {
      border-color: #1e7a38; background: #fff;
      box-shadow: 0 0 0 3px rgba(30,122,56,.13);
    }
    .iw:focus-within .ico { color: #1e7a38; }
    .iw input.inv { border-color: #ef4444; }

    .eye-btn {
      position: absolute; right: 12px;
      background: none; border: none;
      color: #9ca3af; cursor: pointer; font-size: .9rem; padding: 0;
      transition: color .2s;
    }
    .eye-btn:hover { color: #1e7a38; }

    .ferr { display: none; font-size: .73rem; color: #dc2626; margin-top: 4px; }
    .ferr.show { display: block; }

    /* Fila opciones */
    .opts {
      display: flex; justify-content: space-between;
      align-items: center; margin-bottom: 22px;
    }
    .remember {
      display: flex; align-items: center; gap: 8px;
      font-size: .82rem; color: #6b7280; cursor: pointer; user-select: none;
    }
    .remember input { accent-color: #1e7a38; width: 15px; height: 15px; cursor: pointer; }
    .forgot { font-size: .82rem; color: #1e7a38; text-decoration: none; font-weight: 500; }
    .forgot:hover { text-decoration: underline; }

    /* Botón */
    .btn-green {
      width: 100%; padding: 14px;
      background: #1e7a38; color: #fff;
      font-size: .95rem; font-weight: 600; font-family: 'Inter', sans-serif;
      border: none; border-radius: 10px; cursor: pointer;
      box-shadow: 0 4px 18px rgba(30,122,56,.35);
      transition: background .2s, transform .15s, box-shadow .2s;
      letter-spacing: .2px;
    }
    .btn-green:hover { background: #145a27; transform: translateY(-1px); box-shadow: 0 6px 24px rgba(30,122,56,.45); }
    .btn-green:active { transform: translateY(0); }

    /* Links pie */
    .flinks {
      margin-top: 22px; display: flex;
      flex-direction: column; align-items: center; gap: 9px;
    }
    .flinks p { font-size: .82rem; color: #9ca3af; }
    .flinks a.g { color: #1e7a38; font-weight: 600; text-decoration: none; }
    .flinks a.g:hover { text-decoration: underline; }
    .flinks .back {
      font-size: .78rem; color: #9ca3af; text-decoration: none;
      display: flex; align-items: center; gap: 5px;
    }
    .flinks .back:hover { color: #6b7280; }

    @media(max-width: 480px) {
      .right-panel { padding: 36px 18px; }
      .form-box h2 { font-size: 1.55rem; }
    }
  </style>
</head>
<body>

<!-- ── Panel Izquierdo ── -->
<aside class="left-panel">
  <div class="lp-bg"></div>
  <div class="lp-body">
    <div class="brand">
      <div class="brand-icon"><i class="bi bi-shop"></i></div>
      <span class="brand-name">TiendaInsumo</span>
    </div>
    <p class="lp-tag">Acceso al Sistema</p>
    <h2 class="lp-title">Bienvenido<br>de Vuelta</h2>
    <p class="lp-desc">Inicie sesión para acceder al catálogo de insumos, gestionar sus pedidos y descargar sus facturas.</p>
    <div class="info-cards">
      <div class="ic"><i class="bi bi-shield-check-fill"></i><span>Acceso seguro con cifrado de datos</span></div>
      <div class="ic"><i class="bi bi-boxes"></i><span>Inventario y pedidos en tiempo real</span></div>
      <div class="ic"><i class="bi bi-file-earmark-pdf-fill"></i><span>Descarga de facturas en formato PDF</span></div>
    </div>
  </div>
  <div class="lp-footer">© 2026 TiendaInsumo. Todos los derechos reservados.</div>
</aside>

<!-- ── Panel Derecho ── -->
<main class="right-panel">
  <div class="form-box">

    <h2>Iniciar Sesión</h2>
    <p class="sub">Ingrese sus credenciales para acceder al sistema.</p>

    <div class="msg-box err" id="msgBox">
      <i class="bi bi-exclamation-circle-fill"></i>
      <span id="msgText"></span>
    </div>

    <form id="loginForm" action="index.php" method="GET" novalidate>

      <!-- Correo -->
      <div class="fg">
        <div class="iw">
          <i class="bi bi-envelope ico"></i>
          <input type="email" name="email" id="email" placeholder="Correo electrónico" autocomplete="email" required/>
        </div>
        <span class="ferr" id="ferr-email">Ingresa un correo válido.</span>
      </div>

      <!-- Contraseña -->
      <div class="fg">
        <div class="iw">
          <i class="bi bi-lock ico"></i>
          <input type="password" name="password" id="password" placeholder="Contraseña" autocomplete="current-password" required/>
          <button type="button" class="eye-btn" onclick="toggleEye('password','eye1')" aria-label="Ver contraseña">
            <i class="bi bi-eye" id="eye1"></i>
          </button>
        </div>
        <span class="ferr" id="ferr-pwd">Ingresa tu contraseña.</span>
      </div>

      <!-- Opciones -->
      <div class="opts">
        <label class="remember">
          <input type="checkbox" name="remember"/> Recordar sesión
        </label>
        <a href="#" class="forgot">¿Olvidaste tu contraseña?</a>
      </div>

      <button type="submit" class="btn-green">
        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
      </button>
    </form>

    <div class="flinks">
      <p>¿No tiene una cuenta? <a href="register.php" class="g">Regístrese aquí</a></p>
      <a href="index.php" class="back"><i class="bi bi-arrow-left"></i> Volver al inicio</a>
    </div>

  </div>
</main>

<script>
  function toggleEye(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') {
      inp.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      inp.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  }

  document.getElementById('loginForm').addEventListener('submit', function(e) {
    let ok = true;
    const email = document.getElementById('email');
    const pwd   = document.getElementById('password');
    const re    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!re.test(email.value.trim())) {
      email.classList.add('inv');
      document.getElementById('ferr-email').classList.add('show');
      ok = false;
    } else {
      email.classList.remove('inv');
      document.getElementById('ferr-email').classList.remove('show');
    }

    if (!pwd.value.trim()) {
      pwd.classList.add('inv');
      document.getElementById('ferr-pwd').classList.add('show');
      ok = false;
    } else {
      pwd.classList.remove('inv');
      document.getElementById('ferr-pwd').classList.remove('show');
    }

    if (!ok) {
      e.preventDefault();
      const box = document.getElementById('msgBox');
      document.getElementById('msgText').textContent = 'Por favor completa todos los campos correctamente.';
      box.classList.add('show');
    } else {
      // Redirigir al dashboard
      e.preventDefault();
      window.location.href = 'index.php';
    }
  });
</script>
</body>
</html>
