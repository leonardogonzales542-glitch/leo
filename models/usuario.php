<?php
require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function registrar($usuario, $email, $password, $id_rol = 3, $estado = 'Activo')
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $query = 'INSERT INTO usuarios (usuario, email, password, id_rol, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, NOW())';

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('sssis', $usuario, $email, $hashed_password, $id_rol, $estado);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    public function emailExiste($email)
    {
        $query = 'SELECT id_usuario FROM usuarios WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows > 0;
        }
        return false;
    }

    public function obtenerPorEmail($email)
    {
        $query = 'SELECT id_usuario, usuario, email, password, id_rol, estado FROM usuarios WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
            $stmt->close();
            return $usuario;
        }
        return null;
    }

    public function obtenerTodos()
    {
        $query = 'SELECT u.id_usuario, u.usuario, u.email, u.id_rol, u.estado 
                  FROM usuarios u 
                  LEFT JOIN roles r ON u.id_rol = r.id_rol';
        $result = $this->conn->query($query);
        $usuarios = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

    public function obtenerPorRol($id_rol)
    {
        $query = 'SELECT id_usuario, usuario, email, id_rol, estado 
                  FROM usuarios 
                  WHERE id_rol = ?';
        $stmt = $this->conn->prepare($query);
        $usuarios = [];
        if ($stmt) {
            $stmt->bind_param('i', $id_rol);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            $stmt->close();
        }
        return $usuarios;
    }

    public function obtenerEstados()
    {
        return [
            'Activo' => 'Activo',
            'Inactivo' => 'Inactivo'
        ];
    }

    public function actualizar($id_usuario, $datos)
    {
        $query = 'UPDATE usuarios SET usuario = ?, id_rol = ?, estado = ?';
        $types = 'sis';
        $params = [$datos['usuario'], $datos['id_rol'], $datos['estado']];

        if (!empty($datos['email'])) {
            $query .= ', email = ?';
            $types .= 's';
            $params[] = $datos['email'];
        }

        $query .= ' WHERE id_usuario = ?';
        $types .= 'i';
        $params[] = $id_usuario;

        $stmt = $this->conn->prepare($query);
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

    public function eliminar($id_usuario)
    {
        $query = 'DELETE FROM usuarios WHERE id_usuario = ?';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $id_usuario);
            $result = $stmt->execute();
            if (!$result) {
                $error = $stmt->error;
                $stmt->close();
                return 'Error en la base de datos al eliminar: ' . $error;
            }
            $stmt->close();
            return true;
        }
        return 'Error al preparar la consulta de eliminación.';
    }
}
?>