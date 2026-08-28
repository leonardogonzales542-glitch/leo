<?php
require_once "config/database.php";

$sqlFile = "sql/bdtienda.sql";

if (!file_exists($sqlFile)) {
    die("No se encontró el archivo SQL en $sqlFile");
}

$sql = file_get_contents($sqlFile);

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    // Insertamos los roles por defecto si no existen
    $conn->query("INSERT IGNORE INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES (1, 'Administrador', 'Control total del sistema'), (2, 'Vendedor', 'Gestión de ventas y clientes'), (3, 'Cliente', 'Cliente que se registra desde la web');");
    
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: green;'>¡Base de datos instalada exitosamente!</h1>";
    echo "<p>Las tablas (incluyendo <strong>usuarios</strong>) se crearon correctamente.</p>";
    echo "<p>Ya puedes volver a la página de registro y probar.</p>";
    echo "</div>";
} else {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: red;'>Error al crear las tablas:</h1>";
    echo "<p>" . $conn->error . "</p>";
    echo "</div>";
}
?>
