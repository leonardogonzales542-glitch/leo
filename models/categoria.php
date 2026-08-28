<?php
class Categoria {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM categorias ORDER BY id_categoria DESC";
        return $this->conn->query($sql);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($nombre, $descripcion) {
        $stmt = $this->conn->prepare("INSERT INTO categorias (nombre, descripcion, estado) VALUES (?, ?, 1)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        return $stmt->execute();
    }

    public function update($id, $nombre, $descripcion, $estado) {
        $stmt = $this->conn->prepare("UPDATE categorias SET nombre = ?, descripcion = ?, estado = ? WHERE id_categoria = ?");
        $stmt->bind_param("ssii", $nombre, $descripcion, $estado, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
