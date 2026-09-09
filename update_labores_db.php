<?php
require_once "config/database.php";

$sql = "CREATE TABLE IF NOT EXISTS `labores` (
  `id_labor` int PRIMARY KEY AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `lote` varchar(150) NOT NULL,
  `trabajo_realizado` varchar(255) NOT NULL,
  `trabajadores` varchar(255) NOT NULL,
  `valor_original` varchar(100) NOT NULL,
  `valor_calculado` decimal(12,2) NOT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'labores' creada o verificada exitosamente.\n";
} else {
    echo "Error creando la tabla: " . $conn->error . "\n";
}
?>
