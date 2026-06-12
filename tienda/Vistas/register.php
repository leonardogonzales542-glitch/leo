<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TiendaInsumo – Crear Cuenta</title>
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

    /* Círculos decorativos de fondo */
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

    /* Imagen de fondo sutil */
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
      margin-bottom: 48px;
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
      line-height: 1.7;
      max-width: 300px;
    }

    .features {
      margin-top: 44px;
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .features li {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }
    .feat-icon {
      width: 34px; height: 34px;
      background: rgba(255,255,255,0.15);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.85rem;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .feat-text strong {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 2px;
    }
    .feat-text span {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.65);
      line-height: 1.5;
    }

    .left-footer {
      position: relative;
      z-index: 1;
      font-size: 0.75rem;
      color: rgba(255,255,255,0.45);
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
      max-width: 440px;
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

    /* Campos */
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
    .input-wrap input,
    .input-wrap select {
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
      appearance: none;
    }
    .input-wrap select option { color: #111827; }
    .input-wrap input::placeholder { color: #9ca3af; }
    .input-wrap input:focus,
    .input-wrap select:focus {
      border-color: #2e8b47;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(46,139,71,0.12);
    }
    .input-wrap:focus-within .ico { color: #2e8b47; }

    /* Icono chevron para select */
    .input-wrap .chevron {
      position: absolute;
      right: 13px;
      color: #9ca3af;
      font-size: 0.8rem;
      pointer-events: none;
    }

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
    .input-wrap input.invalid,
    .input-wrap select.invalid { border-color: #ef4444; }

    /* Checkbox términos */
    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 24px;
      margin-top: 4px;
    }
    .terms-row input[type="checkbox"] {
      accent-color: #2e8b47;
      width: 15px; height: 15px;
      margin-top: 2px;
      cursor: pointer;
      flex-shrink: 0;
    }
    .terms-row label {
      font-size: 0.8rem;
      color: #6b7280;
      cursor: pointer;
      line-height: 1.5;
    }
    .terms-row a { color: #2e8b47; text-decoration: none; font-weight: 500; }
    .terms-row a:hover { text-decoration: underline; }

    /* Botón principal */
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
    .form-links a {
      color: #2e8b47;
      font-weight: 600;
      text-decoration: none;
    }
    .form-links a:hover { text-decoration: underline; }
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

    <p class="left-tag">Registro de Usuarios</p>
    <h2 class="left-headline">Únase a la<br>Plataforma</h2>
    <p class="left-desc">
      Regístrese de manera autónoma para acceder al catálogo interactivo de insumos
      y descargar su historial de facturas.
    </p>

    <ul class="features">
      <li>
        <div class="feat-icon"><i class="fas fa-list-check"></i></div>
        <div class="feat-text">
          <strong>Catálogo en Tiempo Real</strong>
          <span>Visualice disponibilidad y fichas técnicas de semillas, abonos y agroquímicos.</span>
        </div>
      </li>
      <li>
        <div class="feat-icon"><i class="fas fa-file-invoice"></i></div>
        <div class="feat-text">
          <strong>Historial Contable</strong>
          <span>Descargue copias y duplicados de sus facturas de compra en formato PDF.</span>
        </div>
      </li>
    </ul>
  </div>

  <div class="left-footer">© 2026 TiendaInsumo. Todos los derechos reservados.</div>
</aside>

<!-- Panel derecho -->
<main class="right-panel">
  <div class="form-box">

    <h2>Crear una Cuenta</h2>
    <p class="subtitle">Ingrese la información requerida para registrarse.</p>

    <?php if (!empty($error)): ?>
    <div class="alert error show"><i class="fas fa-circle-exclamation"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
    <div class="alert success show"><i class="fas fa-circle-check"></i><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="../Controladores/RegisterControlador.php" method="POST" id="regForm" novalidate>

      <!-- Nombre completo -->
      <div class="form-group">
        <div class="input-wrap">
          <i class="fas fa-user ico"></i>
          <input type="text" name="nombre" id="nombre"
                 placeholder="Nombre completo o Razón Social"
                 value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                 autocomplete="name" required/>
        </div>
        <span class="field-err" id="err-nombre">Ingresa tu nombre completo.</span>
      </div>

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

      <!-- Rol -->
      <div class="form-group">
        <div class="input-wrap">
          <i class="fas fa-id-badge ico"></i>
          <select name="rol" id="rol" required>
            <option value="cliente" <?= (($_POST['rol'] ?? 'cliente') === 'cliente') ? 'selected' : '' ?>>Rol: Cliente (Predeterminado)</option>
            <option value="vendedor" <?= (($_POST['rol'] ?? '') === 'vendedor') ? 'selected' : '' ?>>Rol: Vendedor</option>
            <option value="admin" <?= (($_POST['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Rol: Administrador</option>
          </select>
          <i class="fas fa-chevron-down chevron"></i>
        </div>
      </div>

      <!-- Contraseña -->
      <div class="form-group">
        <div class="input-wrap">
          <i class="fas fa-lock ico"></i>
          <input type="password" name="password" id="password"
                 placeholder="Contraseña (Mín. 6 caracteres)"
                 autocomplete="new-password" required/>
          <button type="button" class="toggle-btn" onclick="togglePass('password','eye1')" aria-label="Ver contraseña">
            <i class="fas fa-eye" id="eye1"></i>
          </button>
        </div>
        <span class="field-err" id="err-password">Mínimo 6 caracteres.</span>
      </div>

      <!-- Confirmar contraseña -->
      <div class="form-group">
        <div class="input-wrap">
          <i class="fas fa-lock ico"></i>
          <input type="password" name="password_confirm" id="password_confirm"
                 placeholder="Confirmar contraseña"
                 autocomplete="new-password" required/>
          <button type="button" class="toggle-btn" onclick="togglePass('password_confirm','eye2')" aria-label="Ver contraseña">
            <i class="fas fa-eye" id="eye2"></i>
          </button>
        </div>
        <span class="field-err" id="err-confirm">Las contraseñas no coinciden.</span>
      </div>

      <!-- Términos -->
      <div class="terms-row">
        <input type="checkbox" id="terms" name="terms" required/>
        <label for="terms">
          Acepto los términos de tratamiento de datos y políticas comerciales.
        </label>
      </div>

      <button type="submit" class="btn-submit">Registrarse en el Sistema</button>

    </form>

    <div class="form-links">
      <p>¿Ya tiene una Cuenta? <a href="login.php">Inicie sesión aquí</a></p>
      <a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Volver a la página principal</a>
    </div>

  </div>
</main>

<script>
  function togglePass(id, iconId) {
    const inp  = document.getElementById(id);
    const icon = document.getElementById(iconId);
    inp.type   = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }

  document.getElementById('regForm').addEventListener('submit', function(e) {
    let ok = true;

    // Nombre
    const nombre = document.getElementById('nombre');
    setErr('nombre', !nombre.value.trim(), 'err-nombre');
    if (!nombre.value.trim()) ok = false;

    // Email
    const email = document.getElementById('email');
    const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    setErr('email', !validEmail, 'err-email');
    if (!validEmail) ok = false;

    // Contraseña
    const pwd = document.getElementById('password');
    setErr('password', pwd.value.length < 6, 'err-password');
    if (pwd.value.length < 6) ok = false;

    // Confirmar
    const conf = document.getElementById('password_confirm');
    setErr('password_confirm', conf.value !== pwd.value, 'err-confirm');
    if (conf.value !== pwd.value) ok = false;

    // Términos
    if (!document.getElementById('terms').checked) {
      ok = false;
      showAlert('Debes aceptar los términos y condiciones.', 'error');
    }

    if (!ok) e.preventDefault();
  });

  function setErr(fieldId, hasError, errId) {
    const field = document.getElementById(fieldId);
    const err   = document.getElementById(errId);
    if (hasError) {
      field.classList.add('invalid');
      err.classList.add('show');
    } else {
      field.classList.remove('invalid');
      err.classList.remove('show');
    }
  }

  function showAlert(msg, type) {
    let a = document.querySelector('.alert.' + type);
    if (!a) {
      a = document.createElement('div');
      a.className = 'alert ' + type;
      document.querySelector('.subtitle').after(a);
    }
    a.innerHTML = `<i class="fas fa-circle-exclamation"></i> ${msg}`;
    a.classList.add('show');
  }
</script>
</body>
</html>
