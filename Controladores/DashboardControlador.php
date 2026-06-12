<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$bd = "dbtienda";
$port = 3320;

$conn = new mysqli($host, $user, $password, $bd, $port);
{catch(mysql_connect_error $e){}}
if($conn->connect_error){
    die("Error de Conexion: " . $conn->connect_error);
}
?>