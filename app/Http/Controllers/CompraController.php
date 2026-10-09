<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entrada;
use App\Models\DetalleEntrada;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Entrada::with(['proveedor', 'usuario'])->orderBy('id_entrada', 'desc')->get();
        return view('admin.compras', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('estado', 1)->get();
        $productos = Producto::where('estado', 1)->get();
        return view('admin.compras_create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'numero_documento' => 'nullable|string|max:50',
            'observacion' => 'nullable|string|max:255',
            'productos' => 'required|array',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|numeric|min:0.1',
            'productos.*.precio_compra' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $usuario = auth()->user()->id_usuario ?? 1;

            $entrada = Entrada::create([
                'id_proveedor' => $request->id_proveedor,
                'id_usuario' => $usuario,
                'fecha' => now(),
                'numero_documento' => $request->numero_documento,
                'total' => 0,
                'observacion' => $request->observacion
            ]);

            foreach ($request->productos as $prod) {
                $subtotal = $prod['cantidad'] * $prod['precio_compra'];
                $total += $subtotal;

                DetalleEntrada::create([
                    'id_entrada' => $entrada->id_entrada,
                    'id_producto' => $prod['id_producto'],
                    'cantidad' => $prod['cantidad'],
                    'precio_compra' => $prod['precio_compra'],
                    'subtotal' => $subtotal
                ]);

                // Actualizar stock y precio de compra en producto
                $producto = Producto::find($prod['id_producto']);
                $producto->stock_actual += $prod['cantidad'];
                // Se podría promediar o actualizar el precio de compra
                $producto->precio_compra = $prod['precio_compra'];
                $producto->save();
            }

            $entrada->total = $total;
            $entrada->save();

            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Compra registrada con éxito y el inventario ha sido actualizado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la compra: ' . $e->getMessage())->withInput();
        }
    }
}
