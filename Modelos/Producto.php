<?php
require_once __DIR__ . '/../config/conexion.php';
class Producto {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function totalProductos() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM productos");
        return $stmt->fetch()['total'];
    }
    public function alertasStock() {
        $stmt = $this->conn->query("SELECT * FROM productos WHERE stock < stock_min ORDER BY stock ASC");
        return $stmt->fetchAll();
    }
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM productos ORDER BY marca, nombre");
        return $stmt->fetchAll();
    }
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function crear($data) {
        $sql = "INSERT INTO productos (codigo,marca,nombre,categoria,peso,stock,stock_min,precio_compra,precio_venta) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$data['codigo'],$data['marca'],$data['nombre'],$data['categoria'],$data['peso'],$data['stock'],$data['stock_min'],$data['precio_compra'],$data['precio_venta']]);
    }
    public function descontarStock($id, $cantidad) {
        $stmt = $this->conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$cantidad, $id, $cantidad]);
    }
    public function getStockDisponible($id) {
        $stmt = $this->conn->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['stock'] : 0;
    }
}
