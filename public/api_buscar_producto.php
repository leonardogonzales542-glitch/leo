<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if (isset($_GET['q'])) {
    $q = "%" . $_GET['q'] . "%";
    
    $stmt = $conn->prepare("SELECT id_producto, codigo, nombre, unidad_medida, precio_venta, stock_actual FROM productos WHERE nombre LIKE ? OR codigo LIKE ? LIMIT 5");
    $stmt->bind_param("ss", $q, $q);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    
    echo json_encode($productos);
    $stmt->close();
} else {
    echo json_encode([]);
}
$conn->close();
?>
