<?php
class Venta {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar nueva venta
    public function createVenta($id_cliente, $id_vendedor, $subtotal, $descuento, $iva, $total, $metodo_pago, $detalles) {
        try {
            $this->conn->begin_transaction();

            // Generar número de factura único
            $query_factura = "SELECT MAX(id_venta) as last_id FROM ventas";
            $res = $this->conn->query($query_factura);
            $last_id = $res->fetch_assoc()['last_id'] ?? 0;
            $numero_factura = 'FAC-' . str_pad($last_id + 1, 6, '0', STR_PAD_LEFT);
            $fecha = date('Y-m-d H:i:s');
            
            // Insertar Venta
            $stmt = $this->conn->prepare("INSERT INTO ventas (id_cliente, id_vendedor, fecha, numero_factura, subtotal, descuento, iva, total, metodo_pago, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVA')");
            $stmt->bind_param("iissdddds", $id_cliente, $id_vendedor, $fecha, $numero_factura, $subtotal, $descuento, $iva, $total, $metodo_pago);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al registrar la venta: " . $stmt->error);
            }
            
            $id_venta = $this->conn->insert_id;

            // Insertar Detalles y Descontar Inventario
            $stmt_detalle = $this->conn->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, costo_unitario, descuento, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_stock = $this->conn->prepare("UPDATE productos SET stock_actual = stock_actual - ? WHERE id_producto = ? AND stock_actual >= ?");

            foreach ($detalles as $prod) {
                $id_producto = $prod['id_producto'];
                $cantidad = $prod['cantidad'];
                $precio = $prod['precio'];
                $sub_item = $prod['subtotal'];
                $costo = 0; // Se podría obtener de la BD si es necesario

                // Validar y descontar stock
                $stmt_stock->bind_param("dii", $cantidad, $id_producto, $cantidad);
                $stmt_stock->execute();
                
                if ($stmt_stock->affected_rows === 0) {
                    throw new Exception("Stock insuficiente para el producto ID: " . $id_producto);
                }

                // Guardar detalle
                $cero = 0;
                $stmt_detalle->bind_param("iiddddd", $id_venta, $id_producto, $cantidad, $precio, $costo, $cero, $sub_item);
                if (!$stmt_detalle->execute()) {
                    throw new Exception("Error al registrar detalle de venta: " . $stmt_detalle->error);
                }
            }

            $this->conn->commit();
            return $id_venta;

        } catch (Exception $e) {
            $this->conn->rollback();
            return ["error" => $e->getMessage()];
        }
    }

    // Obtener historial de ventas
    public function getAllVentas() {
        $sql = "SELECT v.*, c.nombre as cliente, u.usuario as vendedor 
                FROM ventas v
                LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                LEFT JOIN usuarios u ON v.id_vendedor = u.id_usuario
                ORDER BY v.fecha DESC";
        return $this->conn->query($sql);
    }

    // Obtener venta por ID (para factura)
    public function getVentaById($id_venta) {
        $sql = "SELECT v.*, c.nombre as cliente, c.numero_documento, c.direccion, c.telefono, c.email, u.usuario as vendedor 
                FROM ventas v
                LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                LEFT JOIN usuarios u ON v.id_vendedor = u.id_usuario
                WHERE v.id_venta = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_venta);
        $stmt->execute();
        $venta = $stmt->get_result()->fetch_assoc();

        if ($venta) {
            $sql_detalles = "SELECT d.*, p.nombre, p.codigo, p.unidad_medida 
                             FROM detalle_ventas d
                             INNER JOIN productos p ON d.id_producto = p.id_producto
                             WHERE d.id_venta = ?";
            $stmt_det = $this->conn->prepare($sql_detalles);
            $stmt_det->bind_param("i", $id_venta);
            $stmt_det->execute();
            $venta['detalles'] = $stmt_det->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        return $venta;
    }

    // Anular Venta (Devolver inventario y cambiar estado)
    public function anularVenta($id_venta) {
        try {
            $this->conn->begin_transaction();

            // Verificar que no esté anulada ya
            $check = $this->conn->query("SELECT estado FROM ventas WHERE id_venta = $id_venta");
            if ($check->fetch_assoc()['estado'] === 'ANULADA') {
                throw new Exception("La venta ya se encuentra anulada.");
            }

            // Cambiar estado
            $this->conn->query("UPDATE ventas SET estado = 'ANULADA' WHERE id_venta = $id_venta");

            // Devolver stock
            $detalles = $this->conn->query("SELECT id_producto, cantidad FROM detalle_ventas WHERE id_venta = $id_venta");
            while ($row = $detalles->fetch_assoc()) {
                $id_producto = $row['id_producto'];
                $cantidad = $row['cantidad'];
                $this->conn->query("UPDATE productos SET stock_actual = stock_actual + $cantidad WHERE id_producto = $id_producto");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Estadísticas para el Dashboard de Ventas
    public function getEstadisticas() {
        $stats = [
            'ventas_hoy' => 0,
            'ventas_mes' => 0,
            'productos_vendidos' => 0,
            'total_fiado' => 0
        ];

        // Ventas de hoy
        $hoy = date('Y-m-d');
        $res = $this->conn->query("SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) = '$hoy' AND estado = 'ACTIVA'");
        $stats['ventas_hoy'] = $res->fetch_assoc()['total'] ?? 0;

        // Ventas del mes
        $mes = date('Y-m');
        $res = $this->conn->query("SELECT SUM(total) as total FROM ventas WHERE DATE_FORMAT(fecha, '%Y-%m') = '$mes' AND estado = 'ACTIVA'");
        $stats['ventas_mes'] = $res->fetch_assoc()['total'] ?? 0;

        // Productos vendidos (cantidad)
        $res = $this->conn->query("SELECT SUM(d.cantidad) as total FROM detalle_ventas d INNER JOIN ventas v ON d.id_venta = v.id_venta WHERE v.estado = 'ACTIVA'");
        $stats['productos_vendidos'] = $res->fetch_assoc()['total'] ?? 0;

        // Total Fiado
        $res = $this->conn->query("SELECT SUM(total) as total FROM ventas WHERE metodo_pago = 'Fiado' AND estado = 'ACTIVA'");
        $stats['total_fiado'] = $res->fetch_assoc()['total'] ?? 0;

        return $stats;
    }
}
?>
