<?php
class Devolucion {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las devoluciones con datos básicos
    public function getAll() {
        $sql = "SELECT d.id_devolucion, d.fecha, d.motivo, d.estado, d.id_pedido, d.id_venta,
                       (SELECT SUM(cantidad) FROM detalle_devoluciones WHERE id_devolucion = d.id_devolucion) as total_items
                FROM devoluciones d
                ORDER BY d.fecha DESC";
        return $this->conn->query($sql);
    }

    // Registrar una nueva devolución
    public function createDevolucion($id_pedido, $id_venta, $motivo, $detalles) {
        try {
            $this->conn->begin_transaction();

            $id_pedido = empty($id_pedido) ? NULL : $id_pedido;
            $id_venta = empty($id_venta) ? NULL : $id_venta;

            // Insertar encabezado de devolución
            $stmt = $this->conn->prepare("INSERT INTO devoluciones (id_pedido, id_venta, fecha, motivo, estado) VALUES (?, ?, NOW(), ?, 'Procesada')");
            $stmt->bind_param("iis", $id_pedido, $id_venta, $motivo);
            $stmt->execute();
            $id_devolucion = $this->conn->insert_id;

            // Preparar consultas para detalles e inventario
            $stmt_detalle = $this->conn->prepare("INSERT INTO detalle_devoluciones (id_devolucion, id_producto, cantidad) VALUES (?, ?, ?)");
            $stmt_stock = $this->conn->prepare("UPDATE productos SET stock_actual = stock_actual + ? WHERE id_producto = ?");

            foreach ($detalles as $prod) {
                $id_producto = $prod['id_producto'];
                $cantidad = $prod['cantidad'];

                if ($cantidad > 0) {
                    // Restaurar stock
                    $stmt_stock->bind_param("di", $cantidad, $id_producto);
                    $stmt_stock->execute();

                    // Guardar detalle de devolución
                    $stmt_detalle->bind_param("iid", $id_devolucion, $id_producto, $cantidad);
                    $stmt_detalle->execute();
                }
            }

            // Opcional: Actualizar el estado del pedido/venta a "Devolución"
            if ($id_pedido) {
                $this->conn->query("UPDATE pedidos SET estado = 'Devolución' WHERE id_pedido = " . intval($id_pedido));
            } elseif ($id_venta) {
                $this->conn->query("UPDATE ventas SET estado = 'Devolución' WHERE id_venta = " . intval($id_venta));
            }

            $this->conn->commit();
            return $id_devolucion;
        } catch (Exception $e) {
            $this->conn->rollback();
            return ["error" => $e->getMessage()];
        }
    }
}
?>
