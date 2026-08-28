<?php
class Cliente {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM clientes ORDER BY id_cliente DESC";
        return $this->conn->query($sql);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($tipo_documento, $numero_documento, $nombre, $telefono, $direccion, $email) {
        $stmt = $this->conn->prepare("INSERT INTO clientes (tipo_documento, numero_documento, nombre, telefono, direccion, email, estado) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("ssssss", $tipo_documento, $numero_documento, $nombre, $telefono, $direccion, $email);
        return $stmt->execute();
    }

    public function update($id, $tipo_documento, $numero_documento, $nombre, $telefono, $direccion, $email, $estado) {
        $stmt = $this->conn->prepare("UPDATE clientes SET tipo_documento = ?, numero_documento = ?, nombre = ?, telefono = ?, direccion = ?, email = ?, estado = ? WHERE id_cliente = ?");
        $stmt->bind_param("ssssssii", $tipo_documento, $numero_documento, $nombre, $telefono, $direccion, $email, $estado, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
