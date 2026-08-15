<?php
require_once "config/database.php";

$sql = "SHOW COLUMNS FROM ventas LIKE 'metodo_pago'";
$res = $conn->query($sql);

if ($res->num_rows == 0) {
    $alter = "ALTER TABLE ventas ADD COLUMN metodo_pago VARCHAR(50) DEFAULT 'Efectivo'";
    if ($conn->query($alter)) {
        echo "OK: Columna metodo_pago agregada a la tabla ventas.";
    } else {
        echo "ERROR: " . $conn->error;
    }
} else {
    echo "OK: La columna metodo_pago ya existe.";
}
?>
