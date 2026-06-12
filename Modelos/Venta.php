<?php
require_once __DIR__ . '/../config/conexion.php';
class Venta {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function ventasHoy() {
        $stmt = $this->conn->query("SELECT COALESCE(SUM(total),0) as total FROM ventas WHERE DATE(fecha)=CURDATE() AND estado='Completada'");
        return $stmt->fetch()['total'];
    }
    public function getAll() {
        $sql = "SELECT v.*, c.nombre as cliente_nombre FROM ventas v JOIN clientes c ON v.cliente_id=c.id ORDER BY v.fecha DESC";
        return $this->conn->query($sql)->fetchAll();
    }
    public function getDetalles($venta_id) {
        $stmt = $this->conn->prepare("SELECT dv.*, p.nombre, p.marca FROM detalle_ventas dv JOIN productos p ON dv.producto_id=p.id WHERE dv.venta_id=?");
        $stmt->execute([$venta_id]);
        return $stmt->fetchAll();
    }
    public function crear($cliente_id, $metodo_pago, $items) {
        // $items = array de ['producto_id'=>x,'cantidad'=>y,'precio_unitario'=>z]
        $this->conn->beginTransaction();
        try {
            $total = 0;
            foreach($items as $item) { $total += $item['cantidad'] * $item['precio_unitario']; }
            $codigo = 'VTA-' . str_pad(rand(1,99999), 5, '0', STR_PAD_LEFT);
            $stmt = $this->conn->prepare("INSERT INTO ventas (codigo_venta,cliente_id,total,metodo_pago,estado) VALUES (?,?,?,?,'Completada')");
            $stmt->execute([$codigo, $cliente_id, $total, $metodo_pago]);
            $venta_id = $this->conn->lastInsertId();
            $stmt2 = $this->conn->prepare("INSERT INTO detalle_ventas (venta_id,producto_id,cantidad,precio_unitario) VALUES (?,?,?,?)");
            $stmtStock = $this->conn->prepare("UPDATE productos SET stock=stock-? WHERE id=? AND stock>=?");
            foreach($items as $item) {
                $stmt2->execute([$venta_id,$item['producto_id'],$item['cantidad'],$item['precio_unitario']]);
                $stmtStock->execute([$item['cantidad'],$item['producto_id'],$item['cantidad']]);
            }
            $this->conn->commit();
            return ['ok'=>true,'venta_id'=>$venta_id,'codigo'=>$codigo,'total'=>$total];
        } catch(Exception $e) {
            $this->conn->rollBack();
            return ['ok'=>false,'error'=>$e->getMessage()];
        }
    }
}
