<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

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

        $montoVentas = (float) ($ventasData->monto ?? 0);
        $cantidadVentas = (int) ($ventasData->cantidad ?? 0);

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
