<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(): View
    {
        $ventasHoy = DB::table('ventas')
            ->whereDate('fecha', now()->toDateString())
            ->where('estado', 'ACTIVA')
            ->sum('total');

        $ventasMes = DB::table('ventas')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->where('estado', 'ACTIVA')
            ->sum('total');

        $productosVendidos = DB::table('detalle_ventas')
            ->join('ventas', 'ventas.id_venta', '=', 'detalle_ventas.id_venta')
            ->where('ventas.estado', 'ACTIVA')
            ->sum('cantidad');

        $totalFiado = DB::table('ventas')
            ->where('metodo_pago', 'Fiado')
            ->where('estado', 'ACTIVA')
            ->sum('total');

        $stats = [
            'ventas_hoy' => (float) $ventasHoy,
            'ventas_mes' => (float) $ventasMes,
            'productos_vendidos' => (int) $productosVendidos,
            'total_fiado' => (float) $totalFiado,
        ];

        $ventas = DB::table('ventas')
            ->leftJoin('clientes', 'ventas.id_cliente', '=', 'clientes.id_cliente')
            ->select('ventas.*', 'clientes.nombre as cliente')
            ->orderByDesc('fecha')
            ->get();

        return view('admin.ventas', compact('ventas', 'stats'));
    }

    public function create(): View
    {
        return view('admin.nueva_venta');
    }

    /**
     * Buscar productos por nombre o código (AJAX).
     */
    public function searchProductos(Request $request): JsonResponse
    {
        $q = $request->query('q', '');

        $productos = DB::table('productos')
            ->where('estado', 1)
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('codigo', 'like', "%{$q}%");
            })
            ->select('id_producto', 'codigo', 'nombre', 'precio_venta as precio', 'stock_actual')
            ->limit(10)
            ->get();

        return response()->json($productos);
    }

    /**
     * Buscar clientes por nombre o documento (AJAX).
     */
    public function searchClientes(Request $request): JsonResponse
    {
        $q = $request->query('q', '');

        $clientes = DB::table('clientes')
            ->where('estado', 1)
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('numero_documento', 'like', "%{$q}%");
            })
            ->select('id_cliente', 'nombre', 'numero_documento')
            ->limit(10)
            ->get();

        return response()->json($clientes);
    }

    /**
     * Guardar venta con sus detalles (AJAX JSON).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id_cliente',
            'metodo_pago' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|integer|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'detalles.*.precio' => 'required|numeric|min:0',
            'detalles.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $numeroFactura = 'FAC-'.strtoupper(uniqid());

            $idVenta = DB::table('ventas')->insertGetId([
                'id_cliente' => $validated['id_cliente'],
                'id_vendedor' => auth()->id(),
                'fecha' => now(),
                'numero_factura' => $numeroFactura,
                'subtotal' => $validated['subtotal'],
                'descuento' => 0,
                'iva' => 0,
                'total' => $validated['total'],
                'estado' => 'ACTIVA',
                'metodo_pago' => $validated['metodo_pago'],
            ]);

            foreach ($validated['detalles'] as $detalle) {
                // Obtener costo unitario actual del producto
                $producto = DB::table('productos')
                    ->where('id_producto', $detalle['id_producto'])
                    ->select('precio_compra', 'stock_actual')
                    ->first();

                if (! $producto || $producto->stock_actual < $detalle['cantidad']) {
                    DB::rollBack();

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stock insuficiente para uno de los productos.',
                    ], 422);
                }

                DB::table('detalle_ventas')->insert([
                    'id_venta' => $idVenta,
                    'id_producto' => $detalle['id_producto'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'costo_unitario' => $producto->precio_compra,
                    'descuento' => 0,
                    'subtotal' => $detalle['subtotal'],
                ]);

                // Descontar stock
                DB::table('productos')
                    ->where('id_producto', $detalle['id_producto'])
                    ->decrement('stock_actual', $detalle['cantidad']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'id_venta' => $idVenta,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al guardar la venta: '.$e->getMessage(),
            ], 500);
        }
    }

    public function factura(Request $request)
    {
        $id_venta = $request->query('id');
        if (! $id_venta) {
            return redirect()->route('ventas.index');
        }

        $venta = DB::table('ventas')
            ->leftJoin('clientes', 'ventas.id_cliente', '=', 'clientes.id_cliente')
            ->leftJoin('usuarios', 'ventas.id_vendedor', '=', 'usuarios.id_usuario')
            ->where('ventas.id_venta', $id_venta)
            ->select('ventas.*', 'clientes.nombre as cliente', 'clientes.numero_documento', 'clientes.direccion', 'clientes.telefono', 'usuarios.usuario as vendedor')
            ->first();

        if (! $venta) {
            abort(404, 'Venta no encontrada.');
        }

        $venta = (array) $venta;

        $detalles = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->where('detalle_ventas.id_venta', $id_venta)
            ->select('detalle_ventas.*', 'productos.codigo', 'productos.nombre')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->toArray();

        $venta['detalles'] = $detalles;

        // Empresa config
        $empresa = [
            'nombre' => 'AgriStock',
            'nit' => '900.123.456-7',
            'direccion' => 'Calle Principal #12-34, Ciudad',
            'telefono' => '300 123 4567',
            'email' => 'contacto@agristock.com',
        ];

        $row_emp = DB::table('configuracion')->first();
        if ($row_emp) {
            $empresa['nombre'] = $row_emp->nombre_empresa ?: $empresa['nombre'];
            $empresa['nit'] = $row_emp->nit ?: $empresa['nit'];
            $empresa['direccion'] = $row_emp->direccion ?: $empresa['direccion'];
            $empresa['telefono'] = $row_emp->telefono ?: $empresa['telefono'];
            $empresa['email'] = $row_emp->email ?: $empresa['email'];
        }

        $titulo = 'Factura '.$venta['numero_factura'];

        return view('admin.factura', compact('venta', 'empresa', 'titulo'));
    }

    /**
     * Anular una venta y devolver stock al inventario.
     */
    public function destroy(int $id): RedirectResponse
    {
        $venta = DB::table('ventas')->where('id_venta', $id)->first();

        if (! $venta) {
            return redirect()->route('ventas.index')->with('error', 'Venta no encontrada.');
        }

        if ($venta->estado === 'CANCELADA') {
            return redirect()->route('ventas.index')->with('error', 'La venta ya estaba anulada.');
        }

        DB::beginTransaction();

        try {
            // Devolver stock de cada detalle
            $detalles = DB::table('detalle_ventas')->where('id_venta', $id)->get();

            foreach ($detalles as $detalle) {
                DB::table('productos')
                    ->where('id_producto', $detalle->id_producto)
                    ->increment('stock_actual', $detalle->cantidad);
            }

            // Marcar venta como cancelada
            DB::table('ventas')
                ->where('id_venta', $id)
                ->update(['estado' => 'CANCELADA']);

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta anulada correctamente. El stock fue restaurado.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('ventas.index')->with('error', 'Error al anular la venta: '.$e->getMessage());
        }
    }
}
