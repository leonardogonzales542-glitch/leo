<?php
require_once "config/database.php";

$nit = '900123456-7';
$razon = 'Proveedor Principal S.A.';
$email = 'contacto@proveedor.com';

$res = $conn->query("SELECT id_proveedor FROM proveedores WHERE nit = '$nit'");
if ($res->num_rows == 0) {
    if ($conn->query("INSERT INTO proveedores (nit, razon_social, direccion, telefono, email, estado) VALUES ('$nit', '$razon', 'Calle 123', '3001234567', '$email', 'Activo')")) {
        echo "OK: Proveedor por defecto '$razon' insertado.<br>";
    } else {
        echo "Error insertando proveedor: " . $conn->error;
    }
} else {
    echo "OK: El proveedor ya existe.<br>";
}
?>
