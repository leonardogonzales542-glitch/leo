<?php
/**
 * TiendaInsumo – Setup: Crear base de datos automáticamente
 * Accede en Laragon: http://localhost:8080/tiendainsumo/setup.php
 * ELIMINA este archivo después de usarlo.
 */

// Intentar los puertos comunes de MySQL en Laragon
$host  = 'localhost';
$user  = 'root';
$pass  = '';
$ports = [3306, 3307, 3308, 3320];

$pdo   = null;
$usedPort = null;

foreach ($ports as $port) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2,
        ]);
        $usedPort = $port;
        break;
    } catch (Exception $e) {
        $pdo = null;
    }
}

if (!$pdo) {
    die('<div style="font-family:sans-serif;color:red;padding:20px">
        <h2>❌ No se pudo conectar a MySQL</h2>
        <p>Verifica que <strong>Laragon esté abierto</strong> y que MySQL esté activo (botón verde).</p>
        <p>Puertos probados: ' . implode(', ', $ports) . '</p>
    </div>');
}

$errors = [];
$ok     = [];

// Ejecutar cada sentencia SQL del archivo
$sql = file_get_contents(__DIR__ . '/database.sql');
// Separar por punto y coma (omitiendo vacíos)
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $stmt) {
    if (empty($stmt)) continue;
    try {
        $pdo->exec($stmt);
        // Extraer la primera palabra clave para log
        preg_match('/^\s*(CREATE|INSERT|USE|ALTER|DROP)\s+\S+\s+(?:IF\s+\w+\s+)?`?(\w+)`?/i', $stmt, $m);
        $label = isset($m[1]) ? trim($m[1] . ' ' . ($m[2] ?? '')) : substr($stmt, 0, 50) . '...';
        $ok[] = $label;
    } catch (PDOException $e) {
        // Ignorar "ya existe" (error 1007, 1050, 1062)
        if (in_array($e->errorInfo[1] ?? 0, [1007, 1050, 1062])) {
            $ok[] = '(ya existe) ' . substr($stmt, 0, 50) . '...';
        } else {
            $errors[] = htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Setup – TiendaInsumo</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background:#f0f4f8; padding:30px; }
    .card { background:#fff; border-radius:12px; padding:30px; max-width:640px; margin:auto; box-shadow:0 4px 20px rgba(0,0,0,.1); }
    h2 { margin-top:0; }
    .ok   { color:#16a34a; }
    .err  { color:#dc2626; background:#fef2f2; border-radius:6px; padding:8px 12px; margin:4px 0; }
    .info { color:#6b7280; font-size:.85rem; margin:3px 0; }
    .btn  { display:inline-block; margin-top:20px; padding:12px 24px; background:#16a34a; color:#fff;
            border-radius:8px; text-decoration:none; font-weight:600; }
    .warn { background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:6px; margin-top:20px; }
  </style>
</head>
<body>
<div class="card">
  <h2>⚙️ Setup – TiendaInsumo</h2>
  <p>Conectado a MySQL en el puerto <strong><?= $usedPort ?></strong></p>

  <?php if ($errors): ?>
    <h3 style="color:#dc2626">❌ Errores encontrados</h3>
    <?php foreach ($errors as $e): ?>
      <div class="err"><?= $e ?></div>
    <?php endforeach; ?>
  <?php else: ?>
    <h3 class="ok">✅ Base de datos configurada correctamente</h3>
  <?php endif; ?>

  <details style="margin-top:16px">
    <summary style="cursor:pointer;color:#6b7280">Ver log de operaciones (<?= count($ok) ?> pasos)</summary>
    <?php foreach ($ok as $line): ?>
      <p class="info">✓ <?= htmlspecialchars($line) ?></p>
    <?php endforeach; ?>
  </details>

  <hr style="margin:24px 0">
  <h3>Credenciales de acceso</h3>
  <table>
    <tr><td><b>Email:</b></td><td>admin@tiendainsumo.com</td></tr>
    <tr><td><b>Contraseña:</b></td><td>Admin123!</td></tr>
  </table>
  <a href="login.php" class="btn">→ Ir al Login</a>
  <a href="dashboard.php" class="btn" style="background:#2563eb;margin-left:8px">→ Ir al Dashboard</a>

  <div class="warn">
    <strong>⚠ Seguridad:</strong> Elimina el archivo <code>setup.php</code> después de usarlo.
  </div>
</div>
</body>
</html>
