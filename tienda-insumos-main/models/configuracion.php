<?php
/**
 * Clase Configuracion
 * Modelo encargado de gestionar la tabla de 'configuracion', que guarda los datos generales de la empresa.
 */
class Configuracion {
    private $conn;

    /**
     * Constructor que recibe la conexión de BD y la inyecta al modelo.
     * @param mysqli $db Objeto de conexión.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtiene los datos de configuración de la empresa.
     * @return array|null Los datos de la empresa o null si no se encontraron.
     */
    public function getConfig() {
        // Seleccionamos un único registro de configuración
        $sql = "SELECT * FROM configuracion LIMIT 1";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc(); // Devuelve la fila como array asociativo
        }
        return null;
    }

    /**
     * Actualiza los datos de la configuración general de la empresa.
     * @return bool True si se actualizó correctamente, False en caso de error.
     */
    public function updateConfig($nombre_empresa, $nit, $direccion, $telefono, $email) {
        // Preparamos la consulta SQL UPDATE
        $sql = "UPDATE configuracion SET nombre_empresa = ?, nit = ?, direccion = ?, telefono = ?, email = ? ORDER BY id_configuracion ASC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        // Asignamos las variables a los parámetros de la consulta (s = string)
        $stmt->bind_param("sssss", $nombre_empresa, $nit, $direccion, $telefono, $email);
        
        // Ejecutamos y retornamos true si fue exitoso
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
