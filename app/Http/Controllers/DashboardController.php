<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // 1. Resumen de Ventas (Cantidad y Monto Total)
        $ventasData = DB::table('ventas')
            ->selectRaw('COUNT(*) as cantidad, SUM(total) as monto')
            ->where('estado', '!=', 'CANCELADA')
            ->first();

        $montoVentas = (float) ($ventasData->monto ?? 0);
        $cantidadVentas = (int) ($ventasData->cantidad ?? 0);

        // 2. Resumen de Gastos (Egresos)
        $montoGastos = (float) (DB::table('egresos')->sum('monto') ?? 0);

        // 3. Balance Neto
        $balanceNeto = $montoVentas - $montoGastos;

        // 4. Total de Pedidos, Clientes, Productos y Stock Bajo
        $totalPedidos = DB::table('pedidos')->count();
        $totalClientes = DB::table('clientes')->count();
        $totalProductos = Producto::count();
        $totalStockBajo = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        // 5. Comparativa últimos 6 meses (Barritas móviles Ingresos vs Gastos)
        $mesesNombres = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sept', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
        ];
        $mesesNombresCompletos = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $carbonNow = now();
        $mesActualNombre = $mesesNombresCompletos[$carbonNow->month].' de '.$carbonNow->year;

        $mesesData = [];
        $chartLabels = [];
        $chartIngresos = [];
        $chartGastos = [];
        $chartBalance = [];

        $maxVal = 10000; // baseline para porcentajes de barras

        for ($i = 5; $i >= 0; $i--) {
            $targetDate = (clone $carbonNow)->subMonths($i);
            $year = $targetDate->year;
            $month = $targetDate->month;
            $shortLabel = $mesesNombres[$month];

            $ingresosMes = (float) DB::table('ventas')
                ->where('estado', '!=', 'CANCELADA')
                ->whereYear('fecha', $year)
                ->whereMonth('fecha', $month)
                ->sum('total');

            $gastosMes = (float) DB::table('egresos')
                ->whereYear('fecha', $year)
                ->whereMonth('fecha', $month)
                ->sum('monto');

            if ($ingresosMes > $maxVal) {
                $maxVal = $ingresosMes;
            }
            if ($gastosMes > $maxVal) {
                $maxVal = $gastosMes;
            }

            $chartLabels[] = $shortLabel;
            $chartIngresos[] = $ingresosMes;
            $chartGastos[] = $gastosMes;
            $chartBalance[] = $ingresosMes - $gastosMes;

            $mesesData[] = [
                'label' => $shortLabel,
                'year' => $year,
                'month' => $month,
                'ingresos' => $ingresosMes,
                'gastos' => $gastosMes,
                'is_current' => ($i === 0),
            ];
        }

        // Calcular porcentajes relativos de altura para las microbarras del móvil
        foreach ($mesesData as &$m) {
            $m['pct_ingresos'] = $maxVal > 0 ? max(8, min(100, round(($m['ingresos'] / $maxVal) * 100))) : 8;
            $m['pct_gastos'] = $maxVal > 0 ? max(8, min(100, round(($m['gastos'] / $maxVal) * 100))) : 8;
        }
        unset($m);

        // 6. Proyección a futuro (Balance Futuro para el gráfico estilo fintech)
        $chartProyeccionLabels = ['Oct', 'Nov', 'Dic', 'Ene', 'Feb'];
        $base = max($balanceNeto, 200000);
        $chartProyeccionData = [
            round($base),
            round($base * 1.35),
            round($base * 1.75),
            round($base * 2.2),
            round($base * 2.8),
        ];

        // 7. Últimas 5 ventas reales con información del cliente
        $ultimasVentas = DB::table('ventas')
            ->leftJoin('clientes', 'ventas.id_cliente', '=', 'clientes.id_cliente')
            ->select(
                'ventas.id_venta',
                'ventas.numero_factura',
                'ventas.fecha',
                'ventas.total',
                'ventas.estado',
                'ventas.metodo_pago',
                'clientes.nombre as cliente_nombre'
            )
            ->orderBy('ventas.id_venta', 'desc')
            ->limit(5)
            ->get();

        // 8. Productos con stock bajo reales
        $productosStockBajo = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual', 'asc')
            ->limit(4)
            ->get();

        return view('admin.dashboard', compact(
            'montoVentas',
            'cantidadVentas',
            'montoGastos',
            'balanceNeto',
            'totalPedidos',
            'totalClientes',
            'totalProductos',
            'totalStockBajo',
            'mesActualNombre',
            'mesesData',
            'chartLabels',
            'chartIngresos',
            'chartGastos',
            'chartBalance',
            'chartProyeccionLabels',
            'chartProyeccionData',
            'ultimasVentas',
            'productosStockBajo'
        ));
    }

    public function vendedor()
    {
        return view('vendedor.dashboard');
    }

    public function cliente()
    {
        $productos = DB::table('productos')
            ->leftJoin('categorias', 'productos.id_categoria', '=', 'categorias.id_categoria')
            ->where('productos.estado', 1)
            ->select(
                'productos.id_producto',
                'productos.codigo',
                'productos.nombre',
                'productos.descripcion',
                'productos.precio_venta',
                'productos.imagen',
                'productos.stock_actual',
                'categorias.nombre as categoria_nombre'
            )
            ->orderBy('productos.nombre')
            ->get();

        $nombreUsuario = auth()->user()->usuario ?? 'Cliente';

        return view('cliente.dashboard', compact('productos', 'nombreUsuario'));
    }
}
