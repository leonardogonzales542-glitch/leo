<?php
require_once __DIR__ . '/../config/conexion.php';

class ContabilidadCompleta {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ── RESUMEN GENERAL ─────────────────────────────────────
    public function resumen(): array {
        $ingresos = $this->conn->query("
            SELECT COALESCE(SUM(total),0) AS total FROM ventas WHERE estado='Completada'
        ")->fetch()['total'];

        $gastos = $this->conn->query("
            SELECT COALESCE(SUM(monto),0) AS total FROM gastos WHERE estado='Registrado'
        ")->fetch()['total'];

        $cxc_pendiente = $this->conn->query("
            SELECT COALESCE(SUM(monto_total - monto_pagado),0) AS total
            FROM cuentas_cobrar WHERE estado IN ('Pendiente','Parcial')
        ")->fetch()['total'];

        $cxp_pendiente = $this->conn->query("
            SELECT COALESCE(SUM(monto_total - monto_pagado),0) AS total
            FROM cuentas_pagar WHERE estado IN ('Pendiente','Parcial')
        ")->fetch()['total'];

        $ventas_mes = $this->conn->query("
            SELECT COALESCE(SUM(total),0) AS total FROM ventas
            WHERE estado='Completada' AND MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW())
        ")->fetch()['total'];

        $gastos_mes = $this->conn->query("
            SELECT COALESCE(SUM(monto),0) AS total FROM gastos
            WHERE estado='Registrado' AND MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW())
        ")->fetch()['total'];

        return [
            'ingresos_totales' => (float)$ingresos,
            'gastos_totales'   => (float)$gastos,
            'utilidad_bruta'   => (float)$ingresos - (float)$gastos,
            'cxc_pendiente'    => (float)$cxc_pendiente,
            'cxp_pendiente'    => (float)$cxp_pendiente,
            'ventas_mes'       => (float)$ventas_mes,
            'gastos_mes'       => (float)$gastos_mes,
            'utilidad_mes'     => (float)$ventas_mes - (float)$gastos_mes,
        ];
    }

    // ── MOVIMIENTOS (libro diario) ───────────────────────────
    public function movimientos(string $filtro_tipo = '', string $fecha_desde = '', string $fecha_hasta = '', string $busqueda = ''): array {
        $where = ['1=1'];
        $params = [];

        if ($filtro_tipo) { $where[] = 'm.tipo = ?'; $params[] = $filtro_tipo; }
        if ($fecha_desde) { $where[] = 'm.fecha >= ?'; $params[] = $fecha_desde; }
        if ($fecha_hasta) { $where[] = 'm.fecha <= ?'; $params[] = $fecha_hasta; }
        if ($busqueda)    { $where[] = '(m.concepto LIKE ? OR m.referencia LIKE ?)'; $params[] = "%$busqueda%"; $params[] = "%$busqueda%"; }

        $sql = "
            SELECT m.*, cc.nombre AS cuenta_nombre, c.nombre AS cliente_nombre
            FROM movimientos_contables m
            LEFT JOIN cuentas_contables cc ON m.cuenta_id = cc.id
            LEFT JOIN clientes c ON m.cliente_id = c.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY m.fecha DESC, m.id DESC
            LIMIT 200
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ── INGRESOS / EGRESOS por mes ───────────────────────────
    public function flujoMensual(): array {
        $sql = "
            SELECT
                DATE_FORMAT(fecha,'%Y-%m') AS mes,
                DATE_FORMAT(fecha,'%b %Y') AS mes_label,
                COALESCE(SUM(CASE WHEN tipo IN ('Ingreso','Venta') THEN monto ELSE 0 END),0) AS ingresos,
                COALESCE(SUM(CASE WHEN tipo IN ('Egreso','Compra','Gasto') THEN monto ELSE 0 END),0) AS egresos
            FROM movimientos_contables
            WHERE fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
              AND estado != 'Anulado'
            GROUP BY mes ORDER BY mes ASC
        ";
        return $this->conn->query($sql)->fetchAll();
    }

    // ── CxC ─────────────────────────────────────────────────
    public function cxc(string $estado = ''): array {
        $where = $estado ? "WHERE cc.estado = '$estado'" : '';
        $sql = "
            SELECT cc.*, c.nombre AS cliente_nombre, c.telefono, c.email
            FROM cuentas_cobrar cc
            JOIN clientes c ON cc.cliente_id = c.id
            $where
            ORDER BY cc.fecha_vence ASC, cc.creado_en DESC
        ";
        return $this->conn->query($sql)->fetchAll();
    }

    // ── CxP ─────────────────────────────────────────────────
    public function cxp(string $estado = ''): array {
        $where = $estado ? "WHERE cp.estado = '$estado'" : '';
        $sql = "
            SELECT cp.*, p.nombre AS proveedor_nombre
            FROM cuentas_pagar cp
            LEFT JOIN proveedores p ON cp.proveedor_id = p.id
            $where
            ORDER BY cp.fecha_vence ASC, cp.creado_en DESC
        ";
        return $this->conn->query($sql)->fetchAll();
    }

    // ── GASTOS ───────────────────────────────────────────────
    public function gastos(string $categoria = '', string $desde = '', string $hasta = ''): array {
        $where = ["estado = 'Registrado'"]; $params = [];
        if ($categoria) { $where[] = 'categoria = ?'; $params[] = $categoria; }
        if ($desde)     { $where[] = 'fecha >= ?';    $params[] = $desde; }
        if ($hasta)     { $where[] = 'fecha <= ?';    $params[] = $hasta; }
        $sql = "SELECT * FROM gastos WHERE " . implode(' AND ',$where) . " ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function categoriasGastos(): array {
        return $this->conn->query("SELECT DISTINCT categoria FROM gastos WHERE estado='Registrado' ORDER BY categoria")->fetchAll(PDO::FETCH_COLUMN);
    }

    // ── CUENTAS CONTABLES ────────────────────────────────────
    public function cuentas(): array {
        return $this->conn->query("SELECT * FROM cuentas_contables WHERE activa=1 ORDER BY codigo")->fetchAll();
    }

    // ── REGISTRAR MOVIMIENTO ─────────────────────────────────
    public function registrarMovimiento(array $d): bool {
        $sql = "INSERT INTO movimientos_contables (fecha,tipo,concepto,monto,cuenta_id,referencia,cliente_id,estado,notas)
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $d['fecha'], $d['tipo'], $d['concepto'], $d['monto'],
            $d['cuenta_id'] ?: null, $d['referencia'] ?? null,
            $d['cliente_id'] ?: null, $d['estado'] ?? 'Completado',
            $d['notas'] ?? null
        ]);
    }

    // ── REGISTRAR GASTO ─────────────────────────────────────
    public function registrarGasto(array $d): bool {
        $sql = "INSERT INTO gastos (fecha,categoria,descripcion,monto,metodo_pago,comprobante)
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $d['fecha'], $d['categoria'], $d['descripcion'],
            $d['monto'], $d['metodo_pago'] ?? 'Efectivo', $d['comprobante'] ?? null
        ]);
    }

    // ── REGISTRAR CxC ───────────────────────────────────────
    public function registrarCxC(array $d): bool {
        $sql = "INSERT INTO cuentas_cobrar (cliente_id,concepto,monto_total,fecha_emision,fecha_vence,notas)
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $d['cliente_id'], $d['concepto'], $d['monto_total'],
            $d['fecha_emision'], $d['fecha_vence'] ?: null, $d['notas'] ?? null
        ]);
    }

    // ── REGISTRAR CxP ───────────────────────────────────────
    public function registrarCxP(array $d): bool {
        $sql = "INSERT INTO cuentas_pagar (proveedor_id,concepto,monto_total,fecha_emision,fecha_vence,notas)
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $d['proveedor_id'] ?: null, $d['concepto'], $d['monto_total'],
            $d['fecha_emision'], $d['fecha_vence'] ?: null, $d['notas'] ?? null
        ]);
    }

    // ── PAGAR CxC ───────────────────────────────────────────
    public function pagarCxC(int $id, float $monto): array {
        $row = $this->conn->prepare("SELECT * FROM cuentas_cobrar WHERE id=?");
        $row->execute([$id]); $r = $row->fetch();
        if (!$r) return ['ok'=>false,'error'=>'Registro no encontrado'];
        $nuevo = $r['monto_pagado'] + $monto;
        $estado = $nuevo >= $r['monto_total'] ? 'Pagada' : 'Parcial';
        $this->conn->prepare("UPDATE cuentas_cobrar SET monto_pagado=?, estado=? WHERE id=?")->execute([$nuevo,$estado,$id]);
        return ['ok'=>true,'estado'=>$estado];
    }

    // ── PAGAR CxP ───────────────────────────────────────────
    public function pagarCxP(int $id, float $monto): array {
        $row = $this->conn->prepare("SELECT * FROM cuentas_pagar WHERE id=?");
        $row->execute([$id]); $r = $row->fetch();
        if (!$r) return ['ok'=>false,'error'=>'Registro no encontrado'];
        $nuevo = $r['monto_pagado'] + $monto;
        $estado = $nuevo >= $r['monto_total'] ? 'Pagada' : 'Parcial';
        $this->conn->prepare("UPDATE cuentas_pagar SET monto_pagado=?, estado=? WHERE id=?")->execute([$nuevo,$estado,$id]);
        return ['ok'=>true,'estado'=>$estado];
    }

    // ── ANULAR MOVIMIENTO ────────────────────────────────────
    public function anular(string $tabla, int $id): bool {
        $tablas_validas = ['movimientos_contables','gastos','cuentas_cobrar','cuentas_pagar'];
        if (!in_array($tabla, $tablas_validas)) return false;
        $campo = ($tabla === 'gastos') ? 'estado' : 'estado';
        $stmt = $this->conn->prepare("UPDATE `$tabla` SET estado='Anulado' WHERE id=?");
        return $stmt->execute([$id]);
    }

    // ── BALANCE GENERAL ─────────────────────────────────────
    public function balance(): array {
        $activos    = (float)$this->conn->query("SELECT COALESCE(SUM(total),0) FROM ventas WHERE estado='Completada'")->fetchColumn();
        $gastos     = (float)$this->conn->query("SELECT COALESCE(SUM(monto),0) FROM gastos WHERE estado='Registrado'")->fetchColumn();
        $cxc        = (float)$this->conn->query("SELECT COALESCE(SUM(monto_total-monto_pagado),0) FROM cuentas_cobrar WHERE estado!='Anulada'")->fetchColumn();
        $cxp        = (float)$this->conn->query("SELECT COALESCE(SUM(monto_total-monto_pagado),0) FROM cuentas_pagar WHERE estado!='Anulada'")->fetchColumn();
        return [
            'activos'   => $activos + $cxc,
            'pasivos'   => $gastos + $cxp,
            'patrimonio'=> ($activos + $cxc) - ($gastos + $cxp),
            'cxc'       => $cxc,
            'cxp'       => $cxp,
        ];
    }

    // ── PROVEEDORES ─────────────────────────────────────────
    public function proveedores(): array {
        return $this->conn->query("SELECT * FROM proveedores WHERE estado='Activo' ORDER BY nombre")->fetchAll();
    }

    // ── CLIENTES (para selectores) ───────────────────────────
    public function clientes(): array {
        return $this->conn->query("SELECT id, nombre FROM clientes WHERE estado='Activo' ORDER BY nombre")->fetchAll();
    }

    // ── ESTADÍSTICAS COMPLETAS ───────────────────────────────
    public function estadisticas(): array {

        // Ventas por día de la semana
        $diasSemana = $this->conn->query("
            SELECT DAYNAME(fecha) AS dia, DAYOFWEEK(fecha) AS num,
                   COUNT(*) AS ventas, COALESCE(SUM(total),0) AS total
            FROM ventas WHERE estado='Completada'
              AND fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY dia, num ORDER BY num ASC
        ")->fetchAll();

        // Top 5 clientes por monto
        $topClientes = $this->conn->query("
            SELECT c.nombre, COUNT(v.id) AS pedidos,
                   COALESCE(SUM(v.total),0) AS total
            FROM clientes c
            LEFT JOIN ventas v ON v.cliente_id=c.id AND v.estado='Completada'
            GROUP BY c.id ORDER BY total DESC LIMIT 5
        ")->fetchAll();

        // Gastos por categoría
        $gastosCat = $this->conn->query("
            SELECT categoria,
                   COUNT(*) AS cantidad,
                   COALESCE(SUM(monto),0) AS total
            FROM gastos WHERE estado='Registrado'
            GROUP BY categoria ORDER BY total DESC
        ")->fetchAll();

        // Ventas por método de pago
        $metodos = $this->conn->query("
            SELECT metodo_pago, COUNT(*) AS cantidad,
                   COALESCE(SUM(total),0) AS total
            FROM ventas WHERE estado='Completada'
            GROUP BY metodo_pago ORDER BY total DESC
        ")->fetchAll();

        // Ingresos vs egresos por mes (12 meses)
        $flujo12 = $this->conn->query("
            SELECT DATE_FORMAT(fecha,'%b %Y') AS mes,
                   DATE_FORMAT(fecha,'%Y-%m') AS mes_key,
                   COALESCE(SUM(total),0) AS ingresos
            FROM ventas WHERE estado='Completada'
              AND fecha >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY mes_key ORDER BY mes_key ASC
        ")->fetchAll();

        $gastos12 = $this->conn->query("
            SELECT DATE_FORMAT(fecha,'%b %Y') AS mes,
                   DATE_FORMAT(fecha,'%Y-%m') AS mes_key,
                   COALESCE(SUM(monto),0) AS egresos
            FROM gastos WHERE estado='Registrado'
              AND fecha >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY mes_key ORDER BY mes_key ASC
        ")->fetchAll();

        // Ticket promedio
        $ticketProm = $this->conn->query("
            SELECT COALESCE(AVG(total),0) AS promedio,
                   MAX(total) AS maximo,
                   MIN(total) AS minimo
            FROM ventas WHERE estado='Completada'
        ")->fetch();

        // CxC por estado
        $cxcEstados = $this->conn->query("
            SELECT estado, COUNT(*) AS cantidad,
                   COALESCE(SUM(monto_total-monto_pagado),0) AS saldo
            FROM cuentas_cobrar WHERE estado != 'Anulada'
            GROUP BY estado
        ")->fetchAll();

        // Crecimiento mes actual vs mes anterior
        $crecimiento = $this->conn->query("
            SELECT
                COALESCE(SUM(CASE WHEN MONTH(fecha)=MONTH(NOW())   AND YEAR(fecha)=YEAR(NOW())   THEN total END),0) AS mes_actual,
                COALESCE(SUM(CASE WHEN MONTH(fecha)=MONTH(NOW())-1 AND YEAR(fecha)=YEAR(NOW())   THEN total END),0) AS mes_anterior
            FROM ventas WHERE estado='Completada'
        ")->fetch();

        $pct = $crecimiento['mes_anterior'] > 0
            ? round((($crecimiento['mes_actual'] - $crecimiento['mes_anterior']) / $crecimiento['mes_anterior']) * 100, 1)
            : ($crecimiento['mes_actual'] > 0 ? 100 : 0);

        return [
            'dias_semana'   => $diasSemana,
            'top_clientes'  => $topClientes,
            'gastos_cat'    => $gastosCat,
            'metodos'       => $metodos,
            'flujo12_ing'   => $flujo12,
            'flujo12_egr'   => $gastos12,
            'ticket'        => $ticketProm,
            'cxc_estados'   => $cxcEstados,
            'crecimiento'   => array_merge($crecimiento, ['porcentaje' => $pct]),
        ];
    }
}
