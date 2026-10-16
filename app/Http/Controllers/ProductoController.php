<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->query('filtro', '');

        if ($filtro == 'stock_bajo') {
            // Approximation for now
            $productos = Producto::with(['categoria', 'proveedor'])->whereColumn('stock_actual', '<=', 'stock_minimo')->orderBy('id_producto', 'desc')->get();
            $tituloPagina = 'Stock Bajo';
        } else {
            $productos = Producto::with(['categoria', 'proveedor'])->orderBy('id_producto', 'desc')->get();
            $tituloPagina = 'Inventario Completo';
        }

        $categorias = Categoria::all();
        $proveedores = Proveedor::all();

        return view('admin.inventario', compact('productos', 'categorias', 'proveedores', 'tituloPagina', 'filtro'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();

        return view('admin.agregar_producto', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:productos',
            'nombre' => 'required|string|max:100',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
            'id_proveedor' => 'nullable|integer|exists:proveedores,id_proveedor',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:20',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $imagen_nombre = time().'_'.$request->file('imagen')->getClientOriginalName();
            $request->file('imagen')->move(public_path('img/productos'), $imagen_nombre);
            $data['imagen'] = $imagen_nombre;
        } else {
            $data['imagen'] = 'default.png';
        }

        $data['estado'] = 1;

        Producto::create($data);

        return redirect()->back()->with('success', "Producto '{$data['nombre']}' creado correctamente.");
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo,'.$id.',id_producto',
            'nombre' => 'required|string|max:100',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
            'id_proveedor' => 'nullable|integer|exists:proveedores,id_proveedor',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:20',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'estado' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $imagen_nombre = time().'_'.$request->file('imagen')->getClientOriginalName();
            $request->file('imagen')->move(public_path('img/productos'), $imagen_nombre);
            $data['imagen'] = $imagen_nombre;
        }

        $producto->update($data);

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return redirect()->back()->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se puede eliminar el producto porque tiene movimientos (ventas, entradas, etc).');
        }
    }
}
