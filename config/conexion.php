<?php
/**
 * TiendaInsumo - Configuración de Conexión a la Base de Datos (PDO)
 */

class Database {
    private $host     = "localhost";
    private $ports    = [3306, 3307, 3308, 3320];
    private $db_name  = "tiendainsumo";
    private $username = "root";
    private $password = "";
    public  $conn;

    public function getConnection() {
        $this->conn = null;
        $lastError  = '';

        foreach ($this->ports as $port) {
            try {
                $dsn = "mysql:host={$this->host};port={$port};dbname={$this->db_name};charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
                $this->crearTablasContabilidad();
                return $this->conn;
            } catch (PDOException $e) {
                $lastError = $e->getMessage();
            }
        }

        // Conexión fallida — mostrar página de error amigable
        if (!headers_sent()) {
            http_response_code(500);
        }
        die('
        <!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width,initial-scale=1">
          <title>Error de Conexión – TiendaInsumo</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
          <div class="card shadow border-0 rounded-4 p-5" style="max-width:540px;width:100%">
            <div class="text-center mb-4">
              <span style="font-size:3rem">⚠️</span>
              <h3 class="fw-bold mt-3">Error de Conexión a MySQL</h3>
              <p class="text-muted">No se pudo conectar a la base de datos <strong>tiendainsumo</strong>.</p>
            </div>
            <div class="alert alert-danger rounded-3">
              <small><strong>Detalle:</strong> ' . htmlspecialchars($lastError) . '</small>
            </div>
            <h6 class="fw-bold mt-3">Para solucionar:</h6>
            <ol class="text-muted small">
              <li>Abre <strong>Laragon</strong> y haz clic en <strong>"Iniciar todo"</strong></li>
              <li>Verifica que <strong>MySQL esté en verde</strong></li>
              <li>Vuelve al <a href="/tiendainsumo/dashboard.php" class="text-decoration-none">Dashboard</a></li>
            </ol>
          </div>
        </body>
        </html>
        ');
    }

    /**
     * Crea automáticamente las tablas del módulo contable si no existen.
     * Se ejecuta en cada conexión pero solo actúa si faltan tablas.
     */
    private function crearTablasContabilidad(): void {
        $sqls = [
            "CREATE TABLE IF NOT EXISTS `cuentas_contables` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `codigo` VARCHAR(20) NOT NULL UNIQUE,
                `nombre` VARCHAR(150) NOT NULL,
                `tipo` ENUM('Activo','Pasivo','Patrimonio','Ingreso','Egreso') NOT NULL,
                `descripcion` TEXT DEFAULT NULL,
                `activa` TINYINT(1) NOT NULL DEFAULT 1,
                `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS `proveedores` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `codigo` VARCHAR(20) NOT NULL UNIQUE,
                `nombre` VARCHAR(150) NOT NULL,
                `contacto` VARCHAR(100) DEFAULT NULL,
                `telefono` VARCHAR(30) DEFAULT NULL,
                `email` VARCHAR(100) DEFAULT NULL,
                `estado` ENUM('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
                `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS `gastos` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `fecha` DATE NOT NULL,
                `categoria` VARCHAR(100) NOT NULL,
                `descripcion` VARCHAR(255) NOT NULL,
                `monto` DECIMAL(14,2) NOT NULL,
                `metodo_pago` VARCHAR(50) DEFAULT 'Efectivo',
                `comprobante` VARCHAR(100) DEFAULT NULL,
                `estado` ENUM('Registrado','Anulado') NOT NULL DEFAULT 'Registrado',
                `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS `movimientos_contables` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `fecha` DATE NOT NULL,
                `tipo` ENUM('Ingreso','Egreso','Venta','Compra','Factura','CxC','CxP','Gasto','Ajuste') NOT NULL,
                `concepto` VARCHAR(255) NOT NULL,
                `monto` DECIMAL(14,2) NOT NULL DEFAULT 0,
                `cuenta_id` INT DEFAULT NULL,
                `referencia` VARCHAR(100) DEFAULT NULL,
                `cliente_id` INT DEFAULT NULL,
                `proveedor_id` INT DEFAULT NULL,
                `estado` ENUM('Pendiente','Completado','Anulado') NOT NULL DEFAULT 'Completado',
                `notas` TEXT DEFAULT NULL,
                `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS `cuentas_cobrar` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `cliente_id` INT NOT NULL,
                `venta_id` INT DEFAULT NULL,
                `concepto` VARCHAR(255) NOT NULL,
                `monto_total` DECIMAL(14,2) NOT NULL,
                `monto_pagado` DECIMAL(14,2) NOT NULL DEFAULT 0,
                `fecha_emision` DATE NOT NULL,
                `fecha_vence` DATE DEFAULT NULL,
                `estado` ENUM('Pendiente','Parcial','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Pendiente',
                `notas` TEXT DEFAULT NULL,
                `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS `cuentas_pagar` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `proveedor_id` INT DEFAULT NULL,
                `concepto` VARCHAR(255) NOT NULL,
                `monto_total` DECIMAL(14,2) NOT NULL,
                `monto_pagado` DECIMAL(14,2) NOT NULL DEFAULT 0,
                `fecha_emision` DATE NOT NULL,
                `fecha_vence` DATE DEFAULT NULL,
                `estado` ENUM('Pendiente','Parcial','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Pendiente',
                `notas` TEXT DEFAULT NULL,
                `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // Datos semilla — plan de cuentas básico
            "INSERT IGNORE INTO `cuentas_contables` (`codigo`,`nombre`,`tipo`) VALUES
                ('1001','Caja y Bancos','Activo'),
                ('1002','Cuentas por Cobrar','Activo'),
                ('1003','Inventario de Productos','Activo'),
                ('2001','Cuentas por Pagar','Pasivo'),
                ('3001','Capital Social','Patrimonio'),
                ('4001','Ingresos por Ventas','Ingreso'),
                ('4002','Otros Ingresos','Ingreso'),
                ('5001','Costo de Mercancía Vendida','Egreso'),
                ('5002','Gastos Operativos','Egreso'),
                ('5003','Gastos de Transporte','Egreso'),
                ('5004','Gastos de Servicios','Egreso')",

            "INSERT IGNORE INTO `proveedores` (`codigo`,`nombre`,`contacto`,`telefono`,`email`) VALUES
                ('PROV-001','Distribuidora Alimentos S.A.','Carlos Ramírez','+57 300 111 2222','ventas@dist.com'),
                ('PROV-002','Importadora Pet Food','Ana Torres','+57 315 333 4444','compras@pet.com')",
        ];

        foreach ($sqls as $sql) {
            try {
                $this->conn->exec($sql);
            } catch (PDOException $e) {
                // Silencioso — si falla alguna semilla no rompe el sistema
                error_log('[TiendaInsumo] Setup tabla: ' . $e->getMessage());
            }
        }
    }
}
