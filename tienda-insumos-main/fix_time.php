<?php
require_once "config/database.php";

echo "<h2>Corrigiendo horarios en la base de datos...</h2>";

// Restar 5 horas a los pedidos
$sqlPedidos = "UPDATE pedidos SET fecha = DATE_SUB(fecha, INTERVAL 5 HOUR) WHERE fecha > '2026-08-23 00:00:00'";
if ($conn->query($sqlPedidos)) {
    echo "<p>Pedidos actualizados correctamente: " . $conn->affected_rows . " registros modificados.</p>";
} else {
    echo "<p>Error actualizando pedidos: " . $conn->error . "</p>";
}

// Restar 5 horas a las ventas (por si acaso también se vieron afectadas)
$sqlVentas = "UPDATE ventas SET fecha = DATE_SUB(fecha, INTERVAL 5 HOUR) WHERE fecha > '2026-08-23 00:00:00'";
if ($conn->query($sqlVentas)) {
    echo "<p>Ventas actualizadas correctamente: " . $conn->affected_rows . " registros modificados.</p>";
} else {
    echo "<p>Error actualizando ventas: " . $conn->error . "</p>";
}

echo "<h3>¡Listo! Puedes regresar al panel de pedidos. Ya puedes eliminar este archivo.</h3>";
?>
