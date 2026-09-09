<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../public/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $lote = $_POST['lote'] ?? '';
    $trabajo_realizado = $_POST['trabajo_realizado'] ?? '';
    $trabajadores = $_POST['trabajadores'] ?? '';
    $valor_original = $_POST['valor_original'] ?? '';
    $valor_calculado = $_POST['valor_calculado'] ?? 0;

    // Validaciones básicas
    if (empty($fecha) || empty($lote) || empty($trabajo_realizado) || empty($trabajadores)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Por favor complete todos los campos obligatorios.'
        ];
        header('Location: ../views/admin/registrar_labor.php');
        exit;
    }

    // Preparar datos para inserción (evitar inyección SQL)
    $fecha = $conn->real_escape_string($fecha);
    $lote = $conn->real_escape_string($lote);
    $trabajo_realizado = $conn->real_escape_string($trabajo_realizado);
    $trabajadores = $conn->real_escape_string($trabajadores);
    $valor_original = $conn->real_escape_string($valor_original);
    $valor_calculado = (float) $valor_calculado;

    // Insertar usando MySQLi
    $sql = "INSERT INTO labores (fecha, lote, trabajo_realizado, trabajadores, valor_original, valor_calculado) 
            VALUES ('$fecha', '$lote', '$trabajo_realizado', '$trabajadores', '$valor_original', $valor_calculado)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => '¡Éxito!',
            'message' => 'La labor se ha registrado correctamente.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Hubo un error al registrar la labor: ' . $conn->error
        ];
    }

    header('Location: ../views/admin/registrar_labor.php');
    exit;
} else {
    // Si no es POST, redirigir al listado o form
    header('Location: ../views/admin/registrar_labor.php');
    exit;
}
