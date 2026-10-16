<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = DB::table('pedidos')
            ->leftJoin('clientes', 'pedidos.id_cliente', '=', 'clientes.id_cliente')
            ->select('pedidos.*', 'clientes.nombre as cliente')
            ->orderByDesc('fecha')
            ->get();

        return view('admin.pedidos', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = DB::table('pedidos')
            ->leftJoin('clientes', 'pedidos.id_cliente', '=', 'clientes.id_cliente')
            ->select('pedidos.*', 'clientes.nombre as cliente', 'clientes.numero_documento as cliente_documento')
            ->where('pedidos.id_pedido', $id)
            ->first();

        if (! $pedido) {
            return redirect()->route('pedidos.index')->with('error', 'Pedido no encontrado.');
        }

        $detalles = DB::table('detalle_pedidos')
            ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id_producto')
            ->select('detalle_pedidos.*', 'productos.nombre as producto_nombre')
            ->where('detalle_pedidos.id_pedido', $id)
            ->get();

        $pedido = (array) $pedido;
        $pedido['detalles'] = $detalles->map(function ($d) {
            return (array) $d;
        })->toArray();

        $titulo = 'Recibo de Pedido #'.$pedido['id_pedido'];

        return view('admin.factura_pedido', compact('pedido', 'titulo'));
    }

    public function create()
    {
        return view('admin.nuevo_pedido');
    }

    public function store(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'update_estado') {
            DB::table('pedidos')
                ->where('id_pedido', $request->input('id_pedido'))
                ->update(['estado' => $request->input('estado')]);

            return redirect()->route('pedidos.index')->with('success', 'Estado del pedido actualizado.');
        }

        if ($action === 'save_pedido') {
            try {
                DB::beginTransaction();

                $id_pedido = DB::table('pedidos')->insertGetId([
                    'id_cliente' => $request->input('id_cliente'),
                    'subtotal' => $request->input('subtotal'),
                    'iva' => 0,
                    'total' => $request->input('total'),
                    'metodo_pago' => $request->input('metodo_pago'),
                    'entrega' => $request->input('entrega'),
                    'estado' => 'Pendiente',
                    'fecha' => now(),
                ]);

                $detalles = $request->input('detalles', []);
                foreach ($detalles as $d) {
                    DB::table('detalle_pedidos')->insert([
                        'id_pedido' => $id_pedido,
                        'id_producto' => $d['id_producto'],
                        'cantidad' => $d['cantidad'],
                        'precio_unitario' => $d['precio'],
                        'subtotal' => $d['subtotal'],
                    ]);

                    DB::table('productos')
                        ->where('id_producto', $d['id_producto'])
                        ->decrement('stock_actual', $d['cantidad']);
                }

                DB::commit();

                return response()->json(['status' => 'success', 'message' => 'Pedido generado correctamente.']);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['status' => 'error', 'message' => 'Error al generar el pedido: '.$e->getMessage()]);
            }
        }

        return redirect()->route('pedidos.index');
    }
}
