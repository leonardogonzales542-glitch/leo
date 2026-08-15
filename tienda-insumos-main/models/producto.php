<?php
class Producto {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
                ORDER BY p.id_producto DESC";
        return $this->conn->query($sql);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $imagen = 'default.png') {
        $stmt = $this->conn->prepare("INSERT INTO productos (codigo, nombre, id_categoria, descripcion, unidad_medida, precio_compra, precio_venta, stock_actual, stock_minimo, estado, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)");
        $stmt->bind_param("ssissdddds", $codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $imagen);
        return $stmt->execute();
    }

    public function update($id, $codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $estado, $imagen = null) {
        if ($imagen !== null) {
            $stmt = $this->conn->prepare("UPDATE productos SET codigo = ?, nombre = ?, id_categoria = ?, descripcion = ?, unidad_medida = ?, precio_compra = ?, precio_venta = ?, stock_actual = ?, stock_minimo = ?, estado = ?, imagen = ? WHERE id_producto = ?");
            $stmt->bind_param("ssissddddisi", $codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $estado, $imagen, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE productos SET codigo = ?, nombre = ?, id_categoria = ?, descripcion = ?, unidad_medida = ?, precio_compra = ?, precio_venta = ?, stock_actual = ?, stock_minimo = ?, estado = ? WHERE id_producto = ?");
            $stmt->bind_param("ssissddddii", $codigo, $nombre, $id_categoria, $descripcion, $unidad_medida, $precio_compra, $precio_venta, $stock_actual, $stock_minimo, $estado, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getBajoStock() {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
                WHERE p.stock_actual <= p.stock_minimo 
                ORDER BY (p.stock_actual - p.stock_minimo) ASC";
        return $this->conn->query($sql);
    }
}
?>
