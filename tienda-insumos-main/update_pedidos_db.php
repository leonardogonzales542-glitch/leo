<?php
require_once "config/database.php";

$added = 0;

// Revisar metodo_pago
$res = $conn->query("SHOW COLUMNS FROM pedidos LIKE 'metodo_pago'");
if ($res->num_rows == 0) {
    $conn->query("ALTER TABLE pedidos ADD COLUMN metodo_pago VARCHAR(50) DEFAULT 'Pendiente'");
    echo "OK: Columna metodo_pago agregada a la tabla pedidos.<br>";
    $added++;
}

// Revisar entrega
$res = $conn->query("SHOW COLUMNS FROM pedidos LIKE 'entrega'");
if ($res->num_rows == 0) {
    $conn->query("ALTER TABLE pedidos ADD COLUMN entrega VARCHAR(150) DEFAULT 'Local'");
    echo "OK: Columna entrega agregada a la tabla pedidos.<br>";
    $added++;
}

if ($added == 0) {
    echo "OK: Las columnas metodo_pago y entrega ya existen en la tabla pedidos.<br>";
}
?>
