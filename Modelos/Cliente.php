<?php
require_once __DIR__ . '/../config/conexion.php';
class Cliente {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function totalActivos() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM clientes WHERE estado='Activo'");
        return $stmt->fetch()['total'];
    }
    public function getAll() {
        return $this->conn->query("SELECT * FROM clientes ORDER BY nombre")->fetchAll();
    }
    public function crear($data) {
        $codigo = 'CLI-' . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT);
        $stmt = $this->conn->prepare("INSERT INTO clientes (codigo,nombre,tipo,contacto,telefono,email,estado) VALUES (?,?,?,?,?,?,'Activo')");
        return $stmt->execute([$codigo,$data['nombre'],$data['tipo'],$data['contacto'],$data['telefono'],$data['email']]);
    }
}
