<?php
require_once "config/database.php";

$sql = "
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int PRIMARY KEY AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_vendedor` int,
  `fecha` datetime NOT NULL,
  `subtotal` decimal(12,2) DEFAULT 0,
  `iva` decimal(12,2) DEFAULT 0,
  `total` decimal(12,2) DEFAULT 0,
  `estado` varchar(50) DEFAULT 'Pendiente',
  `observacion` varchar(255),
  FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`)
);

CREATE TABLE IF NOT EXISTS `detalle_pedidos` (
  `id_detalle_pedido` int PRIMARY KEY AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
);

CREATE TABLE IF NOT EXISTS `devoluciones` (
  `id_devolucion` int PRIMARY KEY AUTO_INCREMENT,
  `id_venta` int,
  `id_pedido` int,
  `fecha` datetime NOT NULL,
  `motivo` text NOT NULL,
  `estado` varchar(50) DEFAULT 'Procesada',
  FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`)
);

CREATE TABLE IF NOT EXISTS `detalle_devoluciones` (
  `id_detalle_devolucion` int PRIMARY KEY AUTO_INCREMENT,
  `id_devolucion` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  FOREIGN KEY (`id_devolucion`) REFERENCES `devoluciones` (`id_devolucion`),
  FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
);

CREATE TABLE IF NOT EXISTS `cupones` (
  `id_cupon` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(50) UNIQUE NOT NULL,
  `descuento` decimal(12,2) NOT NULL,
  `tipo` varchar(30) DEFAULT 'Porcentaje',
  `fecha_inicio` datetime,
  `fecha_fin` datetime,
  `estado` boolean DEFAULT true
);

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id_configuracion` int PRIMARY KEY AUTO_INCREMENT,
  `nombre_empresa` varchar(150) NOT NULL,
  `nit` varchar(50),
  `direccion` varchar(255),
  `telefono` varchar(50),
  `email` varchar(100),
  `logo` varchar(255)
);
";

if ($conn->multi_query($sql)) {
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Tablas creadas correctamente.<br>";
} else {
    echo "Error creando tablas: " . $conn->error . "<br>";
}

// Insertar configuración por defecto si está vacía
$check = $conn->query("SELECT COUNT(*) as total FROM configuracion");
if ($check) {
    $row = $check->fetch_assoc();
    if ($row['total'] == 0) {
        $conn->query("INSERT INTO configuracion (nombre_empresa) VALUES ('AgriStock')");
        echo "Configuración inicial guardada.<br>";
    }
}
?>
