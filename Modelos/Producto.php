<?php
require_once __DIR__ . '/../config/conexion.php';

class Producto {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    private function isConnected(): bool {
        return $this->conn !== null;
    }

    public function totalProductos(): int {
        if (!$this->isConnected()) return 0;
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM productos");
        return (int)$stmt->fetch()['total'];
    }

    public function alertasStock(): array {
        if (!$this->isConnected()) return [];
        $stmt = $this->conn->query("SELECT * FROM productos WHERE stock < stock_min ORDER BY stock ASC");
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        if (!$this->isConnected()) return [];
        $stmt = $this->conn->query("SELECT * FROM productos ORDER BY stock ASC, marca, nombre");
        return $stmt->fetchAll();
    }

    public function getById(int $id) {
        if (!$this->isConnected()) return null;
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear(array $data): bool {
        if (!$this->isConnected()) return false;
        $sql = "INSERT INTO productos (codigo,marca,nombre,categoria,peso,stock,stock_min,precio_compra,precio_venta)
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['codigo'], $data['marca'],  $data['nombre'],
            $data['categoria'], $data['peso'], $data['stock'],
            $data['stock_min'], $data['precio_compra'], $data['precio_venta']
        ]);
    }

    public function editar(int $id, array $data): bool {
        if (!$this->isConnected()) return false;
        $sql = "UPDATE productos SET codigo=?, marca=?, nombre=?, categoria=?, peso=?, stock=?, stock_min=?, precio_compra=?, precio_venta=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['codigo'], $data['marca'], $data['nombre'],
            $data['categoria'], $data['peso'], $data['stock'],
            $data['stock_min'], $data['precio_compra'], $data['precio_venta'], $id
        ]);
    }

    public function actualizarStock(int $id, int $cantidad): bool {
        if (!$this->isConnected()) return false;
        $stmt = $this->conn->prepare("UPDATE productos SET stock = GREATEST(0, stock + ?) WHERE id = ?");
        return $stmt->execute([$cantidad, $id]);
    }

    public function eliminar(int $id): bool {
        if (!$this->isConnected()) return false;
        $stmt = $this->conn->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function descontarStock(int $id, int $cantidad): bool {
        if (!$this->isConnected()) return false;
        $stmt = $this->conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$cantidad, $id, $cantidad]);
    }

    public function getStockDisponible(int $id): int {
        if (!$this->isConnected()) return 0;
        $stmt = $this->conn->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? (int)$row['stock'] : 0;
    }
}
