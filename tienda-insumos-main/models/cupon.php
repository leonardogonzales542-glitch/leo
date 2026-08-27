<?php
class Cupon {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCupones() {
        $sql = "SELECT * FROM cupones ORDER BY id_cupon DESC";
        return $this->conn->query($sql);
    }

    public function getCuponById($id) {
        $sql = "SELECT * FROM cupones WHERE id_cupon = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createCupon($codigo, $descuento, $tipo, $fecha_inicio, $fecha_fin, $estado) {
        $sql = "INSERT INTO cupones (codigo, descuento, tipo, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdsssi", $codigo, $descuento, $tipo, $fecha_inicio, $fecha_fin, $estado);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateCupon($id, $codigo, $descuento, $tipo, $fecha_inicio, $fecha_fin, $estado) {
        $sql = "UPDATE cupones SET codigo=?, descuento=?, tipo=?, fecha_inicio=?, fecha_fin=?, estado=? WHERE id_cupon=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdsssii", $codigo, $descuento, $tipo, $fecha_inicio, $fecha_fin, $estado, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteCupon($id) {
        $sql = "DELETE FROM cupones WHERE id_cupon = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
