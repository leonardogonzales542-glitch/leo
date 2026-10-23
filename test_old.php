<?php

$host = '127.0.0.1';
$user = 'root';
$password = '';
$bd = 'tiendadb';
$port = 3306;

try {
    $conn = new mysqli($host, $user, $password, $bd, $port);
    echo 'Conectado correctamente a tiendadb en 3306.';
} catch (mysqli_sql_exception $e) {
    exit('Error de Conexion: '.$e->getMessage());
}
