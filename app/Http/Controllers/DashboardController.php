<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Pedido;

class DashboardController extends Controller
{
    public function admin()
    {
        // 1. Resumen de Ventas (Cantidad y Monto Total)
        // Como Venta es un modelo básico generado sin configuración explícita, usamos DB query builder por seguridad.
        $ventasData = DB::table('ventas')
            ->selectRaw('COUNT(*) as cantidad, SUM(total) as monto')
            ->where('estado', '!=', 'CANCELADA')
            ->first();
            
        $montoVentas = (float)($ventasData->monto ?? 0);
        $cantidadVentas = (int)($ventasData->cantidad ?? 0);

        // 2. Total de Pedidos
        $totalPedidos = DB::table('pedidos')->count();

        // 3. Total de Clientes
        $totalClientes = DB::table('clientes')->count();

        // 4. Productos Registrados
        $totalProductos = Producto::count();

        // 5. Productos con Stock Bajo
        $totalStockBajo = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        return view('admin.dashboard', compact(
            'montoVentas',
            'cantidadVentas',
            'totalPedidos',
            'totalClientes',
            'totalProductos',
            'totalStockBajo'
        ));
    }

    public function vendedor()
    {
        return view('vendedor.dashboard');
    }

    public function cliente()
    {
        return view('cliente.dashboard');
    }
}
