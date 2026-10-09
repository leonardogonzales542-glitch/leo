<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('admin.categorias', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $data['estado'] = $request->has('estado');

        Categoria::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $data['estado'] = $request->has('estado');

        $categoria->update($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy($id)
    {
        try {
            Categoria::destroy($id);
            return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')->with('error', 'No se puede eliminar porque hay productos usando esta categoría.');
        }
    }
}
