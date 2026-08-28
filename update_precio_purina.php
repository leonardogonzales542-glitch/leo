<?php
require_once "config/database.php";

$codigo = 'PUR-001';
$precio_venta_cop = 149500;
$precio_compra_cop = 120000;

$sql = "UPDATE productos SET precio_venta = ?, precio_compra = ? WHERE codigo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dds", $precio_venta_cop, $precio_compra_cop, $codigo);

if ($stmt->execute()) {
    echo "OK: Precio actualizado a $" . number_format($precio_venta_cop, 0, ',', '.') . " COP.";
} else {
    echo "ERROR: " . $stmt->error;
}
?>
