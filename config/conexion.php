<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Configuración de Conexión a la Base de Datos (PDO)
 */

class Database {
    private $host = "localhost";
    private $db_name = "tiendainsumo";
    private $username = "root";
    private $password = ""; // Contraseña por defecto en Laragon
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            // Configurar PDO para reportar excepciones de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Configurar modo de obtención por defecto a arreglo asociativo
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // En producción, es recomendable loguear el error y no mostrar detalles sensibles
            error_log("Error de conexión: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
