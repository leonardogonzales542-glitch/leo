-- -------------------------------------------------------------
-- PurinaStock - Sistema de Control de Inventario y Ventas
-- Script SQL de inicialización de Base de Datos
-- -------------------------------------------------------------

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `tiendainsumo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tiendainsumo`;

-- 1. Tabla de Clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo` VARCHAR(20) NOT NULL UNIQUE,
  `nombre` VARCHAR(150) NOT NULL,
  `tipo` ENUM('Minorista', 'Mayorista', 'Distribuidor') NOT NULL DEFAULT 'Minorista',
  `contacto` VARCHAR(100) DEFAULT NULL,
  `telefono` VARCHAR(30) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabla de Usuarios para el sistema de autenticación
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `rol` ENUM('cliente', 'vendedor', 'admin') NOT NULL DEFAULT 'cliente',
  `password` VARCHAR(255) NOT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabla de Productos (Stock de Purinas)
CREATE TABLE IF NOT EXISTS `productos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo` VARCHAR(50) NOT NULL UNIQUE,
  `marca` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `categoria` VARCHAR(100) NOT NULL,
  `peso` VARCHAR(20) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `stock_min` INT NOT NULL DEFAULT 5,
  `precio_compra` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `precio_venta` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabla de Ventas (Cabecera de Factura)
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo_venta` VARCHAR(20) NOT NULL UNIQUE,
  `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `cliente_id` INT NOT NULL,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `metodo_pago` VARCHAR(50) NOT NULL,
  `estado` ENUM('Completada', 'Pendiente', 'Cancelada') NOT NULL DEFAULT 'Completada',
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 4. Detalle de Ventas (Relación muchos a muchos con Productos)
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `venta_id` INT NOT NULL,
  `producto_id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`venta_id`) REFERENCES `ventas`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- INSERTAR DATOS INICIALES (SEMILLA)
-- -------------------------------------------------------------

-- Clientes iniciales
INSERT INTO `clientes` (`codigo`, `nombre`, `tipo`, `contacto`, `telefono`, `email`, `estado`) VALUES
('CLI-001', 'Veterinaria San Francisco', 'Mayorista', 'Dr. Carlos Mendoza', '+57 312 456 7890', 'carlos@vetsanfrancisco.com', 'Activo'),
('CLI-002', 'Pet Shop Huellitas', 'Distribuidor', 'Sra. Sandra Restrepo', '+57 300 765 4321', 'ventas@huellitaspetshop.com', 'Activo'),
('CLI-003', 'María Clara Restrepo', 'Minorista', 'María Clara Restrepo', '+57 310 987 6543', 'mariac@gmail.com', 'Activo'),
('CLI-004', 'Clínica Veterinaria Mascotas Lindas', 'Mayorista', 'Dra. Patricia Gómez', '+57 315 222 3344', 'contacto@mascotaslindas.com', 'Activo'),
('CLI-005', 'Juan Carlos Pérez', 'Minorista', 'Juan Carlos Pérez', '+57 321 888 9900', 'juanp@hotmail.com', 'Inactivo');

-- Productos iniciales
INSERT INTO `productos` (`codigo`, `marca`, `nombre`, `categoria`, `peso`, `stock`, `stock_min`, `precio_compra`, `precio_venta`) VALUES
('PUR-DOG-ADU-15', 'Dog Chow', 'Adultos Medianos y Grandes - Sabor Carne', 'Adulto', '15 Kg', 3, 10, 32000.00, 45000.00),
('PUR-PRO-PUP-03', 'Pro Plan', 'Puppy Razas Pequeñas - Desarrollo Óptimo', 'Cachorro', '3 Kg', 0, 5, 12500.00, 18000.00),
('PUR-EXC-SEN-12', 'Excellent', 'Adulto Sensitive - Cuidado Especial Piel', 'Adulto / Cuidado Especial', '12 Kg', 4, 8, 27000.00, 38000.00),
('PUR-DOG-CACH-22', 'Dog Chow', 'Cachorros Minis y Pequeños - Vida Sana', 'Cachorro', '22.7 Kg', 2, 6, 44000.00, 62000.00),
('PUR-PRO-ADU-15', 'Pro Plan', 'Adulto Mediano y Grande OptiHealth', 'Adulto', '15 Kg', 24, 8, 52000.00, 75000.00),
('PUR-CAT-ADU-08', 'Cat Chow', 'Gatos Adultos Delicias de Pescado', 'Felinos (Otros)', '8 Kg', 15, 5, 18000.00, 26000.00);
