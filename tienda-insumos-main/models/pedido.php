<?php
class Pedido {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo pedido
    public function createPedido($id_cliente, $id_vendedor, $subtotal, $iva, $total, $metodo_pago, $entrega, $detalles) {
        try {
            $this->conn->begin_transaction();

            $fecha = date('Y-m-d H:i:s');
            $estado = 'Pendiente';
            
            // Insertar Pedido
            $stmt = $this->conn->prepare("INSERT INTO pedidos (id_cliente, id_vendedor, fecha, subtotal, iva, total, estado, metodo_pago, entrega) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdddsss", $id_cliente, $id_vendedor, $fecha, $subtotal, $iva, $total, $estado, $metodo_pago, $entrega);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al crear el pedido: " . $stmt->error);
            }
            
            $id_pedido = $this->conn->insert_id;

            // Insertar Detalles
            $stmt_detalle = $this->conn->prepare("INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt_stock = $this->conn->prepare("UPDATE productos SET stock_actual = stock_actual - ? WHERE id_producto = ? AND stock_actual >= ?");

            foreach ($detalles as $prod) {
                $id_producto = $prod['id_producto'];
                $cantidad = $prod['cantidad'];
                $precio = $prod['precio'];
                $sub_item = $prod['subtotal'];

                // Descontar stock para reservar el producto
                $stmt_stock->bind_param("dii", $cantidad, $id_producto, $cantidad);
                $stmt_stock->execute();
                
                if ($stmt_stock->affected_rows === 0) {
                    throw new Exception("Stock insuficiente para el producto ID: " . $id_producto);
                }

                // Guardar detalle
                $stmt_detalle->bind_param("iiddd", $id_pedido, $id_producto, $cantidad, $precio, $sub_item);
                if (!$stmt_detalle->execute()) {
                    throw new Exception("Error al registrar detalle de pedido: " . $stmt_detalle->error);
                }
            }

            $this->conn->commit();
            return $id_pedido;

        } catch (Exception $e) {
            $this->conn->rollback();
            return ["error" => $e->getMessage()];
        }
    }

    // Obtener historial de pedidos
    public function getAllPedidos() {
        $sql = "SELECT p.*, c.nombre as cliente, u.usuario as vendedor 
                FROM pedidos p
                LEFT JOIN clientes c ON p.id_cliente = c.id_cliente
                LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
                ORDER BY p.fecha DESC";
        return $this->conn->query($sql);
    }

    // Actualizar estado del pedido
    public function updateEstado($id_pedido, $nuevo_estado) {
        try {
            $this->conn->begin_transaction();

            $check = $this->conn->query("SELECT estado FROM pedidos WHERE id_pedido = $id_pedido");
            $estado_actual = $check->fetch_assoc()['estado'];

            if ($estado_actual === 'Cancelado' && $nuevo_estado !== 'Cancelado') {
                throw new Exception("Un pedido cancelado no puede cambiar de estado.");
            }

            // Cambiar estado
            $stmt = $this->conn->prepare("UPDATE pedidos SET estado = ? WHERE id_pedido = ?");
            $stmt->bind_param("si", $nuevo_estado, $id_pedido);
            $stmt->execute();

            // Si se cancela, devolver stock
            if ($nuevo_estado === 'Cancelado' && $estado_actual !== 'Cancelado') {
                $detalles = $this->conn->query("SELECT id_producto, cantidad FROM detalle_pedidos WHERE id_pedido = $id_pedido");
                while ($row = $detalles->fetch_assoc()) {
                    $id_producto = $row['id_producto'];
                    $cantidad = $row['cantidad'];
                    $this->conn->query("UPDATE productos SET stock_actual = stock_actual + $cantidad WHERE id_producto = $id_producto");
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
?>
