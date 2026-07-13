<?php
require_once __DIR__ . '/../config/conexion.php';

class Cliente {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    private function isConnected(): bool {
        return $this->conn !== null;
    }

    public function totalActivos(): int {
        if (!$this->isConnected()) return 0;
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM clientes WHERE estado = 'Activo'");
        return (int)$stmt->fetch()['total'];
    }

    public function getAll(): array {
        if (!$this->isConnected()) return [];
        return $this->conn->query("SELECT * FROM clientes ORDER BY nombre")->fetchAll();
    }

    public function crear(array $data): bool {
        if (!$this->isConnected()) return false;
        $codigo = 'CLI-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $stmt   = $this->conn->prepare(
            "INSERT INTO clientes (codigo, nombre, tipo, contacto, telefono, email, estado)
             VALUES (?, ?, ?, ?, ?, ?, 'Activo')"
        );
        return $stmt->execute([
            $codigo,
            $data['nombre'], $data['tipo'],
            $data['contacto'], $data['telefono'], $data['email']
        ]);
    }
}
