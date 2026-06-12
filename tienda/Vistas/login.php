<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TiendaInsumo – Iniciar Sesión</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: stretch;
      background: #f0f4f0;
    }

    /* ===== PANEL IZQUIERDO ===== */
    .left-panel {
      display: none;
      flex: 0 0 420px;
      background: linear-gradient(160deg, #1a6b2f 0%, #2e8b47 45%, #3aaa5c 100%);
      flex-direction: column;
      justify-content: space-between;
      padding: 48px 44px;
      position: relative;
      overflow: hidden;
      color: #fff;
    }
    @media(min-width: 900px) { .left-panel { display: flex; } }

    .left-panel::before {
      content: '';
      position: absolute;
      width: 340px; height: 340px;
      border-radius: 50%;
      border: 60px solid rgba(255,255,255,0.06);
      top: -80px; right: -80px;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 260px; height: 260px;
      border-radius: 50%;
      border: 50px solid rgba(255,255,255,0.05);
      bottom: -60px; left: -60px;
    }

    .left-bg-img {
      position: absolute;
      inset: 0;
      background-image: url('https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=800&q=60');
      background-size: cover;
      background-position: center;
      opacity: 0.08;
    }

    .left-content { position: relative; z-index: 1; }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 56px;
    }
    .brand-icon {
      width: 40px; height: 40px;
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
      backdrop-filter: blur(6px);
    }
    .brand-name {
      font-size: 1.25rem;
      font-weight: 700;
      letter-spacing: -0.3px;
    }

    .left-tag {
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.6);
      margin-bottom: 16px;
    }
    .left-headline {
      font-size: 2.6rem;
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: -1px;
      margin-bottom: 20px;
    }
    .left-desc {
      font-size: 0.875rem;
      color: rgba(255,255,255,0.75);
      line-height: 1.75;
      max-width: 300px;
    }

    .info-cards {
      margin-top: 44px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
    .info-card {
      display: flex;
      align-items: center;
      gap: 14px;
      background: rgba(255,255,255,0.1);
      border-radius: 12px;
      padding: 14px 18px;
      backdrop-filter: blur(4px);
    }
    .info-card i {
      font-size: 1.1rem;
      color: rgba(255,255,255,0.85);
      width: 20px;
      text-align: center;
    }
    .info-card span {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.8);
      line-height: 1.4;
    }

    .left-footer {
      position: relative;
      z-index: 1;
      font-size: 0.75rem;
      color: rgba(255,255,255,0.4);
    }

    /* ===== PANEL DERECHO ===== */
    .right-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 32px;
      background: #fff;
    }

    .form-box {
      width: 100%;
      max-width: 400px;
    }

    .form-box h2 {
      font-size: 1.9rem;
      font-weight: 800;
      color: #111827;
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }
    .form-box .subtitle {
      font-size: 0.875rem;
      color: #6b7280;
      margin-bottom: 32px;
    }

    /* Alertas */
    .alert {
      display: none;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.83rem;
      margin-bottom: 20px;
      gap: 8px;
      align-items: center;
    }
    .alert.error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
    .alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .alert.show { display: flex; }

    .form-group { margin-bottom: 16px; }

    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .input-wrap .ico {
      position: absolute;
      left: 14px;
      color: #9ca3af;
      font-size: 0.88rem;
      pointer-events: none;
      transition: color 0.2s;
    }
    .input-wrap input {
      width: 100%;
      padding: 13px 14px 13px 40px;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      font-size: 0.875rem;
      font-family: 'Inter', sans-serif;
      color: #111827;
      background: #f9fafb;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .input-wrap input::placeholder { color: #9ca3af; }
    .input-wrap input:focus {
      border-color: #2e8b47;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(46,139,71,0.12);
    }
    .input-wrap:focus-within .ico { color: #2e8b47; }
    .input-wrap input.invalid { border-color: #ef4444; }

    .toggle-btn {
      position: absolute;
      right: 13px;
      background: none;
      border: none;
      color: #9ca3af;
      cursor: pointer;
      font-size: 0.88rem;
      padding: 0;
      transition: color 0.2s;
    }
    .toggle-btn:hover { color: #2e8b47; }

    .field-err {
      display: none;
      font-size: 0.75rem;
      color: #dc2626;
      margin-top: 5px;
    }
    .field-err.show { display: block; }

    /* Fila opciones */
    .options-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .remember {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.82rem;
      color: #6b7280;
      cursor: pointer;
      user-select: none;
    }
    .remember input[type="checkbox"] {
      accent-color: #2e8b47;
      width: 15px; height: 15px;
      cursor: pointer;
    }
    .forgot {
      font-size: 0.82rem;
      color: #2e8b47;
      text-decoration: none;
      font-weight: 500;
    }
    .forgot:hover { text-decoration: underline; }

    /* Botón */
    .btn-submit {
      width: 100%;
      padding: 14px;
      background: #2e8b47;
      color: #fff;
      font-size: 0.95rem;
      font-weight: 600;
      font-family: 'Inter', sans-serif;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 18px rgba(46,139,71,0.35);
      letter-spacing: 0.2px;
    }
    .btn-submit:hover  { background: #236e38; transform: translateY(-1px); box-shadow: 0 6px 24px rgba(46,139,71,0.45); }
    .btn-submit:active { transform: translateY(0); }

    .form-links {
      margin-top: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
    .form-links p {
      font-size: 0.82rem;
      color: #9ca3af;
    }
    .form-links a.green {
      color: #2e8b47;
      font-weight: 600;
      text-decoration: none;
    }
    .form-links a.green:hover { text-decoration: underline; }
    .form-links .back-link {
      font-size: 0.8rem;
      color: #9ca3af;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .form-links .back-link:hover { color: #6b7280; }

    @media(max-width: 480px) {
      .right-panel { padding: 36px 20px; }
      .form-box h2 { font-size: 1.6rem; }
    }
  </style>
</head>
<body>

<!-- Panel izquierdo -->
<aside class="left-panel">
  <div class="left-bg-img"></div>

  <div class="left-content">
    <div class="brand">
      <div class="brand-icon"><i class="fas fa-seedling"></i></div>
      <span class="brand-name">TiendaInsumo</span>
    </div>

    <p class="left-tag">Acceso al Sistema</p>
    <h2 class="left-headline">Bienvenido<br>de Vuelta</h2>
    <p class="left-desc">
      Inicie sesión para acceder al catálogo de insumos, gestionar sus pedidos
      y descargar sus facturas en cualquier momento.
    </p>

    <div class="info-cards">
      <div class="info-card">
        <i class="fas fa-shield-halved"></i>
        <span>Acceso seguro con cifrado de datos</span>
      </div>
      <div class="info-card">
        <i class="fas fa-boxes-stacked"></i>
        <span>Inventario y pedidos en tiempo real</span>
      </div>
      <div class="info-card">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>Descarga de facturas en formato PDF</span>
      </div>
    </div>
  </div>

  <div class="left-footer">© 2026 TiendaInsumo. Todos los derechos reservados.</div>
</aside>

<!-- Panel derecho -->
<main class="right-panel">
  <div class="form-box">

    <h2>Iniciar Sesión</h2>
    <p class="subtitle">Ingrese sus credenciales para acceder al sistema.</p>

    <?php if (!empty($error)): ?>
    <div class="alert error show"><i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
    <div class="alert success show"><i class="fas fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="../Controladores/LoginControlador.php" method="POST" id="loginForm" novalidate>

      <!-- Correo -->
      <div class="form-group">
        <div class="input-wrap">
          <i class="fas fa-envelope ico"></i>
          <input type="email" name="email" id="email"
                 placeholder="Correo electrónico"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 autocomplete="email" required/>
        </div>
        <span class="field-err" id="err-email">Ingresa un correo válido.</span>
      </div>

      <!-- Contraseña -->
      <div class="form-group">
        <div class="input-wrap">
          <i class="fas fa-lock ico"></i>
          <input type="password" name="password" id="password"
                 placeholder="Contraseña"
                 autocomplete="current-password" required/>
          <button type="button" class="toggle-btn" onclick="togglePass()" aria-label="Ver contraseña">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
        <span class="field-err" id="err-password">Ingresa tu contraseña.</span>
      </div>

      <!-- Opciones -->
      <div class="options-row">
        <label class="remember">
          <input type="checkbox" name="remember" id="remember"/>
          Recordar sesión
        </label>
        <a href="recuperar.php" class="forgot">¿Olvidaste tu contraseña?</a>
      </div>

      <button type="submit" class="btn-submit">Ingresar al Sistema</button>

    </form>

    <div class="form-links">
      <p>¿No tiene una Cuenta? <a href="register.php" class="green">Regístrese aquí</a></p>
      <a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Volver a la página principal</a>
    </div>

  </div>
</main>

<script>
  function togglePass() {
    const inp  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    inp.type   = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }

  document.getElementById('loginForm').addEventListener('submit', function(e) {
    let ok = true;

    const email = document.getElementById('email');
    const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    if (!validEmail) {
      email.classList.add('invalid');
      document.getElementById('err-email').classList.add('show');
      ok = false;
    } else {
      email.classList.remove('invalid');
      document.getElementById('err-email').classList.remove('show');
    }

    const pwd = document.getElementById('password');
    if (!pwd.value.trim()) {
      pwd.classList.add('invalid');
      document.getElementById('err-password').classList.add('show');
      ok = false;
    } else {
      pwd.classList.remove('invalid');
      document.getElementById('err-password').classList.remove('show');
    }

    if (!ok) e.preventDefault();
  });
</script>
</body>
</html>
