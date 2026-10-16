<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionController extends Controller
{
    public function index()
    {
        $devoluciones = DB::table('devoluciones')
            ->leftJoin('ventas', 'devoluciones.id_venta', '=', 'ventas.id_venta')
            ->select('devoluciones.*')
            ->orderByDesc('fecha')
            ->get();

        return view('admin.devoluciones', compact('devoluciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'origen' => 'required|in:venta,pedido',
            'id_origen' => 'required|integer|min:1',
            'motivo' => 'required|string|max:1000',
            'productos' => 'required|array|min:1',
        ]);

        $origen = $request->input('origen');
        $idOrigen = (int) $request->input('id_origen');
        $motivo = $request->input('motivo');
        $productos = $request->input('productos'); // [id_producto => cantidad]

        // Verificar que el origen existe
        if ($origen === 'venta') {
            $existe = DB::table('ventas')->where('id_venta', $idOrigen)->exists();
            if (! $existe) {
                return back()->with('error', 'No se encontró la venta #'.$idOrigen)->withInput();
            }
        } else {
            $existe = DB::table('pedidos')->where('id_pedido', $idOrigen)->exists();
            if (! $existe) {
                return back()->with('error', 'No se encontró el pedido #'.$idOrigen)->withInput();
            }
        }

        DB::beginTransaction();

        try {
            $totalItems = 0;

            $idDevolucion = DB::table('devoluciones')->insertGetId([
                'id_venta' => $origen === 'venta' ? $idOrigen : null,
                'id_pedido' => $origen === 'pedido' ? $idOrigen : null,
                'motivo' => $motivo,
                'estado' => 'Procesado',
                'fecha' => now(),
            ]);

            foreach ($productos as $idProducto => $cantidad) {
                $cantidad = (float) $cantidad;
                if ($cantidad <= 0) {
                    continue;
                }

                $totalItems += $cantidad;

                // Devolver stock
                DB::table('productos')
                    ->where('id_producto', $idProducto)
                    ->increment('stock_actual', $cantidad);
            }

            // Actualizar total_items si la columna existe
            DB::table('devoluciones')
                ->where('id_devolucion', $idDevolucion)
                ->update(['total_items' => $totalItems]);

            DB::commit();

            return redirect()->route('devoluciones.index')
                ->with('success', 'Devolución registrada correctamente. Stock actualizado.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Error al registrar la devolución: '.$e->getMessage())->withInput();
        }
    }
}
