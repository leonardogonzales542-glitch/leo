<?php
require_once __DIR__ . '/../config/database.php';

class Proveedor
{
    public static function obtenerTodos()
    {
        global $conn;
        $query = 'SELECT * FROM proveedores ORDER BY id_proveedor DESC';
        $result = $conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function registrar($nit, $razon_social, $direccion, $telefono, $email, $estado = 'Activo')
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO proveedores (nit, razon_social, direccion, telefono, email, estado) VALUES (?, ?, ?, ?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('ssssss', $nit, $razon_social, $direccion, $telefono, $email, $estado);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    public static function nitExiste($nit)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT id_proveedor FROM proveedores WHERE nit = ?');
        if ($stmt) {
            $stmt->bind_param('s', $nit);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            return $exists;
        }
        return false;
    }

    public static function obtenerEstados()
    {
        return [
            'Activo' => 'Activo',
            'Inactivo' => 'Inactivo'
        ];
    }

    public function actualizar($id_proveedor, $datos)
    {
        global $conn;
        $query = 'UPDATE proveedores SET razon_social = ?, nit = ?, telefono = ?, email = ?, direccion = ?, estado = ?';
        $types = 'ssssss';
        $params = [$datos['razon_social'], $datos['nit'], $datos['telefono'], $datos['email'], $datos['direccion'], $datos['estado']];

        $query .= ' WHERE id_proveedor = ?';
        $types .= 'i';
        $params[] = $id_proveedor;

        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $result = $stmt->execute();
            if (!$result) {
                $error = $stmt->error;
                $stmt->close();
                return 'Error en la base de datos: ' . $error;
            }
            $stmt->close();
            return true;
        }
        return 'Error al preparar la consulta de actualización.';
    }
}
