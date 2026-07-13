-- ============================================================
-- TiendaInsumo - Módulo de Contabilidad
-- Tablas adicionales para el sistema contable completo
-- ============================================================
USE `tiendainsumo`;

-- Cuentas contables (plan de cuentas)
CREATE TABLE IF NOT EXISTS `cuentas_contables` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `codigo`      VARCHAR(20)  NOT NULL UNIQUE,
  `nombre`      VARCHAR(150) NOT NULL,
  `tipo`        ENUM('Activo','Pasivo','Patrimonio','Ingreso','Egreso') NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `activa`      TINYINT(1) NOT NULL DEFAULT 1,
  `creado_en`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Movimientos contables (libro diario)
CREATE TABLE IF NOT EXISTS `movimientos_contables` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `fecha`        DATE NOT NULL,
  `tipo`         ENUM('Ingreso','Egreso','Venta','Compra','Factura','CxC','CxP','Gasto','Ajuste') NOT NULL,
  `concepto`     VARCHAR(255) NOT NULL,
  `monto`        DECIMAL(14,2) NOT NULL DEFAULT 0,
  `cuenta_id`    INT DEFAULT NULL,
  `referencia`   VARCHAR(100) DEFAULT NULL,  -- código venta, factura, etc.
  `cliente_id`   INT DEFAULT NULL,
  `proveedor_id` INT DEFAULT NULL,
  `estado`       ENUM('Pendiente','Completado','Anulado') NOT NULL DEFAULT 'Completado',
  `notas`        TEXT DEFAULT NULL,
  `creado_en`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cuenta_id`)    REFERENCES `cuentas_contables`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`cliente_id`)   REFERENCES `clientes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `codigo`    VARCHAR(20)  NOT NULL UNIQUE,
  `nombre`    VARCHAR(150) NOT NULL,
  `contacto`  VARCHAR(100) DEFAULT NULL,
  `telefono`  VARCHAR(30)  DEFAULT NULL,
  `email`     VARCHAR(100) DEFAULT NULL,
  `estado`    ENUM('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Cuentas por cobrar (CxC)
CREATE TABLE IF NOT EXISTS `cuentas_cobrar` (
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
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`venta_id`)   REFERENCES `ventas`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB;

-- Cuentas por pagar (CxP)
CREATE TABLE IF NOT EXISTS `cuentas_pagar` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `proveedor_id`  INT DEFAULT NULL,
  `concepto`      VARCHAR(255) NOT NULL,
  `monto_total`   DECIMAL(14,2) NOT NULL,
  `monto_pagado`  DECIMAL(14,2) NOT NULL DEFAULT 0,
  `fecha_emision` DATE NOT NULL,
  `fecha_vence`   DATE DEFAULT NULL,
  `estado`        ENUM('Pendiente','Parcial','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Pendiente',
  `notas`         TEXT DEFAULT NULL,
  `creado_en`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Gastos operativos
CREATE TABLE IF NOT EXISTS `gastos` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `fecha`       DATE NOT NULL,
  `categoria`   VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `monto`       DECIMAL(14,2) NOT NULL,
  `metodo_pago` VARCHAR(50)  DEFAULT 'Efectivo',
  `comprobante` VARCHAR(100) DEFAULT NULL,
  `estado`      ENUM('Registrado','Anulado') NOT NULL DEFAULT 'Registrado',
  `creado_en`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── DATOS INICIALES ──────────────────────────────────────────

-- Plan de cuentas básico
INSERT IGNORE INTO `cuentas_contables` (`codigo`,`nombre`,`tipo`) VALUES
('1001','Caja y Bancos','Activo'),
('1002','Cuentas por Cobrar','Activo'),
('1003','Inventario de Productos','Activo'),
('2001','Cuentas por Pagar','Pasivo'),
('2002','Préstamos por Pagar','Pasivo'),
('3001','Capital Social','Patrimonio'),
('4001','Ingresos por Ventas','Ingreso'),
('4002','Otros Ingresos','Ingreso'),
('5001','Costo de Mercancía Vendida','Egreso'),
('5002','Gastos Operativos','Egreso'),
('5003','Gastos de Transporte','Egreso'),
('5004','Gastos de Servicios','Egreso');

-- Proveedor de ejemplo
INSERT IGNORE INTO `proveedores` (`codigo`,`nombre`,`contacto`,`telefono`,`email`) VALUES
('PROV-001','Distribuidora Alimentos S.A.','Carlos Ramírez','+57 300 111 2222','ventas@distribuidos.com'),
('PROV-002','Importadora Pet Food','Ana Torres','+57 315 333 4444','compras@petfood.com');
