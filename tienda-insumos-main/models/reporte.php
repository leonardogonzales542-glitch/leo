<?php
class Reporte {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getKpis() {
        // Ingresos del mes (Ventas + Pedidos)
        $mes = date('m');
        $anio = date('Y');
        
        $sqlVentas = "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio' AND estado != 'Cancelado'";
        $sqlPedidos = "SELECT SUM(total) as total FROM pedidos WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio' AND estado != 'Cancelado'";
        
        $ingresos = 0;
        $resV = $this->conn->query($sqlVentas);
        if ($resV && $row = $resV->fetch_assoc()) $ingresos += floatval($row['total']);
        
        $resP = $this->conn->query($sqlPedidos);
        if ($resP && $row = $resP->fetch_assoc()) $ingresos += floatval($row['total']);

        // Cantidad de Pedidos y Ventas este mes
        $sqlCountP = "SELECT COUNT(*) as c FROM pedidos WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio'";
        $sqlCountV = "SELECT COUNT(*) as c FROM ventas WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio'";
        
        $totalOperaciones = 0;
        $resCp = $this->conn->query($sqlCountP);
        if ($resCp && $row = $resCp->fetch_assoc()) $totalOperaciones += intval($row['c']);
        $resCv = $this->conn->query($sqlCountV);
        if ($resCv && $row = $resCv->fetch_assoc()) $totalOperaciones += intval($row['c']);

        // Productos en stock crítico (< 5)
        $sqlStock = "SELECT COUNT(*) as c FROM productos WHERE stock_actual < 5 AND estado = 1";
        $stockCritico = 0;
        $resS = $this->conn->query($sqlStock);
        if ($resS && $row = $resS->fetch_assoc()) $stockCritico = intval($row['c']);

        return [
            'ingresos_mes' => $ingresos,
            'total_operaciones' => $totalOperaciones,
            'stock_critico' => $stockCritico
        ];
    }

    public function getVentasPorMes() {
        // Últimos 6 meses
        $datos = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = date('m', strtotime("-$i months"));
            $anio = date('Y', strtotime("-$i months"));
            $nombre_mes = date('M', strtotime("-$i months")); // ej. Aug
            
            $sqlVentas = "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio' AND estado != 'Cancelado'";
            $sqlPedidos = "SELECT SUM(total) as total FROM pedidos WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio' AND estado != 'Cancelado'";
            
            $total = 0;
            $resV = $this->conn->query($sqlVentas);
            if ($resV && $row = $resV->fetch_assoc()) $total += floatval($row['total']);
            $resP = $this->conn->query($sqlPedidos);
            if ($resP && $row = $resP->fetch_assoc()) $total += floatval($row['total']);

            $datos['meses'][] = $nombre_mes;
            $datos['totales'][] = $total;
        }
        return $datos;
    }

    public function getTopProductos() {
        // Top 5 productos más vendidos sumando ventas y pedidos
        $sql = "
            SELECT p.id_producto, p.nombre, p.precio_venta, 
                   (IFNULL(v.cant_ventas, 0) + IFNULL(ped.cant_pedidos, 0)) as total_vendido
            FROM productos p
            LEFT JOIN (
                SELECT id_producto, SUM(cantidad) as cant_ventas 
                FROM detalle_ventas 
                GROUP BY id_producto
            ) v ON p.id_producto = v.id_producto
            LEFT JOIN (
                SELECT id_producto, SUM(cantidad) as cant_pedidos 
                FROM detalle_pedidos 
                GROUP BY id_producto
            ) ped ON p.id_producto = ped.id_producto
            ORDER BY total_vendido DESC
            LIMIT 5
        ";
        return $this->conn->query($sql);
    }
}
?>
