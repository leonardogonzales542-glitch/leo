<?php
/**
 * TiendaInsumo - Setup automático tablas de Contabilidad
 * Accede a: http://localhost:8080/tiendainsumo/setup_contabilidad.php
 */
require_once __DIR__ . '/config/conexion.php';

$db   = new Database();
$conn = $db->getConnection();
$logs = [];
$ok   = true;

$tablas = [

'cuentas_contables' => "CREATE TABLE IF NOT EXISTS `cuentas_contables` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `codigo`      VARCHAR(20)  NOT NULL UNIQUE,
  `nombre`      VARCHAR(150) NOT NULL,
  `tipo`        ENUM('Activo','Pasivo','Patrimonio','Ingreso','Egreso') NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `activa`      TINYINT(1) NOT NULL DEFAULT 1,
  `creado_en`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

'proveedores' => "CREATE TABLE IF NOT EXISTS `proveedores` (
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `codigo`    VARCHAR(20)  NOT NULL UNIQUE,
  `nombre`    VARCHAR(150) NOT NULL,
  `contacto`  VARCHAR(100) DEFAULT NULL,
  `telefono`  VARCHAR(30)  DEFAULT NULL,
  `email`     VARCHAR(100) DEFAULT NULL,
  `estado`    ENUM('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

'gastos' => "CREATE TABLE IF NOT EXISTS `gastos` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `fecha`       DATE NOT NULL,
  `categoria`   VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `monto`       DECIMAL(14,2) NOT NULL,
  `metodo_pago` VARCHAR(50) DEFAULT 'Efectivo',
  `comprobante` VARCHAR(100) DEFAULT NULL,
  `estado`      ENUM('Registrado','Anulado') NOT NULL DEFAULT 'Registrado',
  `creado_en`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

'movimientos_contables' => "CREATE TABLE IF NOT EXISTS `movimientos_contables` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `fecha`        DATE NOT NULL,
  `tipo`         ENUM('Ingreso','Egreso','Venta','Compra','Factura','CxC','CxP','Gasto','Ajuste') NOT NULL,
  `concepto`     VARCHAR(255) NOT NULL,
  `monto`        DECIMAL(14,2) NOT NULL DEFAULT 0,
  `cuenta_id`    INT DEFAULT NULL,
  `referencia`   VARCHAR(100) DEFAULT NULL,
  `cliente_id`   INT DEFAULT NULL,
  `proveedor_id` INT DEFAULT NULL,
  `estado`       ENUM('Pendiente','Completado','Anulado') NOT NULL DEFAULT 'Completado',
  `notas`        TEXT DEFAULT NULL,
  `creado_en`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

'cuentas_cobrar' => "CREATE TABLE IF NOT EXISTS `cuentas_cobrar` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id`   INT NOT NULL,
  `venta_id`     INT DEFAULT NULL,
  `concepto`     VARCHAR(255) NOT NULL,
  `monto_total`  DECIMAL(14,2) NOT NULL,
  `monto_pagado` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `fecha_emision`DATE NOT NULL,
  `fecha_vence`  DATE DEFAULT NULL,
  `estado`       ENUM('Pendiente','Parcial','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Pendiente',
  `notas`        TEXT DEFAULT NULL,
  `creado_en`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

'cuentas_pagar' => "CREATE TABLE IF NOT EXISTS `cuentas_pagar` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `proveedor_id`  INT DEFAULT NULL,
  `concepto`      VARCHAR(255) NOT NULL,
  `monto_total`   DECIMAL(14,2) NOT NULL,
  `monto_pagado`  DECIMAL(14,2) NOT NULL DEFAULT 0,
  `fecha_emision` DATE NOT NULL,
  `fecha_vence`   DATE DEFAULT NULL,
  `estado`        ENUM('Pendiente','Parcial','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Pendiente',
  `notas`         TEXT DEFAULT NULL,
  `creado_en`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

];

foreach ($tablas as $nombre => $sql) {
    try {
        $conn->exec($sql);
        $logs[] = ['ok'=>true, 'msg'=>"✅ Tabla <strong>$nombre</strong> creada/verificada correctamente."];
    } catch (PDOException $e) {
        $logs[] = ['ok'=>false, 'msg'=>"❌ Error en <strong>$nombre</strong>: " . $e->getMessage()];
        $ok = false;
    }
}

// Insertar datos semilla
$seeds = [
    "INSERT IGNORE INTO `cuentas_contables` (`codigo`,`nombre`,`tipo`) VALUES
        ('1001','Caja y Bancos','Activo'),('1002','Cuentas por Cobrar','Activo'),
        ('1003','Inventario de Productos','Activo'),('2001','Cuentas por Pagar','Pasivo'),
        ('2002','Préstamos por Pagar','Pasivo'),('3001','Capital Social','Patrimonio'),
        ('4001','Ingresos por Ventas','Ingreso'),('4002','Otros Ingresos','Ingreso'),
        ('5001','Costo de Mercancía Vendida','Egreso'),('5002','Gastos Operativos','Egreso'),
        ('5003','Gastos de Transporte','Egreso'),('5004','Gastos de Servicios','Egreso')",

    "INSERT IGNORE INTO `proveedores` (`codigo`,`nombre`,`contacto`,`telefono`,`email`) VALUES
        ('PROV-001','Distribuidora Alimentos S.A.','Carlos Ramírez','+57 300 111 2222','ventas@distribuidos.com'),
        ('PROV-002','Importadora Pet Food','Ana Torres','+57 315 333 4444','compras@petfood.com')",
];

foreach ($seeds as $seed) {
    try {
        $conn->exec($seed);
        $logs[] = ['ok'=>true, 'msg'=>"✅ Datos semilla insertados correctamente."];
    } catch (PDOException $e) {
        $logs[] = ['ok'=>false, 'msg'=>"⚠️ Semilla (no crítico): " . $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Setup Contabilidad – TiendaInsumo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
  <div class="card shadow border-0 rounded-4 p-5" style="max-width:600px;width:100%">
    <div class="text-center mb-4">
      <span style="font-size:2.5rem"><?= $ok ? '✅' : '⚠️' ?></span>
      <h3 class="fw-bold mt-2">Setup de Contabilidad</h3>
      <p class="text-muted">TiendaInsumo — Creación de tablas del módulo contable</p>
    </div>

    <?php foreach($logs as $log): ?>
    <div class="alert <?= $log['ok'] ? 'alert-success' : 'alert-danger' ?> py-2 mb-2 rounded-3">
      <?= $log['msg'] ?>
    </div>
    <?php endforeach; ?>

    <div class="mt-4 d-grid gap-2">
      <?php if($ok): ?>
      <a href="Vistas/contabilidad_full.php" class="btn btn-success rounded-pill py-2 fw-bold">
        <i class="bi bi-calculator me-2"></i>Ir al Módulo de Contabilidad
      </a>
      <a href="Vistas/estadisticas.php" class="btn btn-primary rounded-pill py-2 fw-bold">
        <i class="bi bi-bar-chart-line me-2"></i>Ver Estadísticas
      </a>
      <?php endif; ?>
      <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill py-2">
        ← Volver al Dashboard
      </a>
    </div>
  </div>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet"/>
</body>
</html>
