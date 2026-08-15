<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Modelo: Usuario
 */

require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene un usuario por su correo electrónico.
     * 
     * @param string $email
     * @return array|null
     */
    public function getByEmail(string $email): ?array {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            return $user ? $user : null;
        } catch (PDOException $e) {
            error_log("Error al buscar usuario por email: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica si un correo electrónico ya está registrado.
     * 
     * @param string $email
     * @return bool
     */
    public function emailExiste(string $email): bool {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            return (bool)$stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error al verificar existencia de email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     * 
     * @param string $nombre
     * @param string $email
     * @param string $password Contraseña en texto plano
     * @param string $rol Rol ('admin', 'vendedor', 'cliente')
     * @return bool
     */
    public function registrar(string $nombre, string $email, string $password, string $rol = 'cliente'): bool {
        try {
            // Cifrar la contraseña
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt = $this->conn->prepare(
                "INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES (?, ?, ?, ?, 'Activo')"
            );
            return $stmt->execute([$nombre, $email, $passwordHash, $rol]);
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>
