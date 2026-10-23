<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fecha_inicio = $request->query('fecha_inicio', date('Y-m-d', strtotime('-30 days')));
        $fecha_fin = $request->query('fecha_fin', date('Y-m-d'));

        if ($fecha_inicio > $fecha_fin) {
            $temp = $fecha_inicio;
            $fecha_inicio = $fecha_fin;
            $fecha_fin = $temp;
        }

        // 1. Total Ingresos (Ventas)
        $resVentas = DB::table('ventas')
            ->selectRaw('SUM(total) as monto, COUNT(id_venta) as cantidad')
            ->where('estado', 'ACTIVA')
            ->whereRaw('DATE(fecha) BETWEEN ? AND ?', [$fecha_inicio, $fecha_fin])
            ->first();

        $totalIngresos = $resVentas->monto ?? 0;
        $totalVentas = $resVentas->cantidad ?? 0;

        // 2. Total Pedidos
        $resPedidos = DB::table('pedidos')
            ->selectRaw('SUM(total) as monto, COUNT(id_pedido) as cantidad')
            ->where('estado', '!=', 'Cancelado')
            ->whereRaw('DATE(fecha) BETWEEN ? AND ?', [$fecha_inicio, $fecha_fin])
            ->first();

        $totalPedidos = $resPedidos->cantidad ?? 0;

        // 3. Productos más vendidos (Top 5)
        $topProductos = DB::table('detalle_ventas as d')
            ->join('ventas as v', 'd.id_venta', '=', 'v.id_venta')
            ->join('productos as p', 'd.id_producto', '=', 'p.id_producto')
            ->selectRaw('p.nombre, SUM(d.cantidad) as total_vendido')
            ->where('v.estado', 'ACTIVA')
            ->whereRaw('DATE(v.fecha) BETWEEN ? AND ?', [$fecha_inicio, $fecha_fin])
            ->groupBy('d.id_producto', 'p.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // 4. Datos para el gráfico
        $datosGrafico = DB::table('ventas')
            ->selectRaw('DATE(fecha) as fecha_dia, SUM(total) as total_dia')
            ->where('estado', 'ACTIVA')
            ->whereRaw('DATE(fecha) BETWEEN ? AND ?', [$fecha_inicio, $fecha_fin])
            ->groupBy('fecha_dia')
            ->orderBy('fecha_dia', 'asc')
            ->get();

        $fechas_chart = [];
        $totales_chart = [];

        foreach ($datosGrafico as $row) {
            $fechas_chart[] = date('d M', strtotime($row->fecha_dia));
            $totales_chart[] = $row->total_dia;
        }

        return view('admin.reportes', compact(
            'fecha_inicio', 'fecha_fin',
            'totalIngresos', 'totalVentas', 'totalPedidos',
            'topProductos', 'fechas_chart', 'totales_chart'
        ));
    }
}
