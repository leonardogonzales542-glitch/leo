<?php
require_once __DIR__ . '/../config/conexion.php';

class Contabilidad {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /** Total comprado por cada cliente */
    public function comprasPorCliente(): array {
        $sql = "
            SELECT
                c.id,
                c.codigo,
                c.nombre,
                c.tipo,
                c.email,
                c.telefono,
                COUNT(v.id)        AS total_pedidos,
                COALESCE(SUM(v.total), 0) AS total_comprado,
                MAX(v.fecha)       AS ultima_compra
            FROM clientes c
            LEFT JOIN ventas v ON v.cliente_id = c.id AND v.estado = 'Completada'
            GROUP BY c.id
            ORDER BY total_comprado DESC
        ";
        return $this->conn->query($sql)->fetchAll();
    }

    /** Resumen general de contabilidad */
    public function resumenGeneral(): array {
        $sql = "
            SELECT
                COALESCE(SUM(total), 0)                                      AS ingresos_totales,
                COALESCE(SUM(CASE WHEN DATE(fecha) = CURDATE() THEN total END), 0) AS ingresos_hoy,
                COALESCE(SUM(CASE WHEN WEEK(fecha) = WEEK(NOW()) THEN total END), 0) AS ingresos_semana,
                COALESCE(SUM(CASE WHEN MONTH(fecha) = MONTH(NOW()) AND YEAR(fecha) = YEAR(NOW()) THEN total END), 0) AS ingresos_mes,
                COUNT(*)                                                      AS total_ventas,
                COUNT(DISTINCT cliente_id)                                    AS clientes_compraron
            FROM ventas
            WHERE estado = 'Completada'
        ";
        return $this->conn->query($sql)->fetch();
    }

    /** Ventas por mes (últimos 6 meses) */
    public function ventasPorMes(): array {
        $sql = "
            SELECT
                DATE_FORMAT(fecha, '%Y-%m') AS mes,
                DATE_FORMAT(fecha, '%b %Y') AS mes_label,
                COALESCE(SUM(total), 0)     AS total,
                COUNT(*)                    AS cantidad
            FROM ventas
            WHERE estado = 'Completada'
              AND fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mes
            ORDER BY mes ASC
        ";
        return $this->conn->query($sql)->fetchAll();
    }

    /** Top 5 productos más vendidos */
    public function topProductos(): array {
        $sql = "
            SELECT
                p.nombre,
                p.marca,
                SUM(dv.cantidad)                        AS unidades_vendidas,
                SUM(dv.cantidad * dv.precio_unitario)   AS total_generado
            FROM detalle_ventas dv
            JOIN productos p ON dv.producto_id = p.id
            JOIN ventas v    ON dv.venta_id    = v.id
            WHERE v.estado = 'Completada'
            GROUP BY p.id
            ORDER BY total_generado DESC
            LIMIT 5
        ";
        return $this->conn->query($sql)->fetchAll();
    }

    /** Historial de ventas de un cliente */
    public function historialCliente(int $cliente_id): array {
        $sql = "
            SELECT
                v.codigo_venta,
                v.fecha,
                v.total,
                v.metodo_pago,
                v.estado,
                GROUP_CONCAT(p.marca, ' ', p.nombre, ' x', dv.cantidad ORDER BY p.nombre SEPARATOR ' | ') AS productos
            FROM ventas v
            JOIN detalle_ventas dv ON dv.venta_id    = v.id
            JOIN productos p       ON dv.producto_id = p.id
            WHERE v.cliente_id = ?
            GROUP BY v.id
            ORDER BY v.fecha DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll();
    }

    /** Ventas por método de pago */
    public function ventasPorMetodo(): array {
        $sql = "
            SELECT
                metodo_pago,
                COUNT(*)            AS cantidad,
                SUM(total)          AS total
            FROM ventas
            WHERE estado = 'Completada'
            GROUP BY metodo_pago
            ORDER BY total DESC
        ";
        return $this->conn->query($sql)->fetchAll();
    }
}
