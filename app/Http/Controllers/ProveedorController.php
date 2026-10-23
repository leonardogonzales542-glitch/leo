<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = DB::table('proveedores')->get();
        $estados = [
            'Activo' => 'Activo',
            'Inactivo' => 'Inactivo',
        ];

        return view('admin.proveedores', compact('proveedores', 'estados'));
    }

    public function store(Request $request)
    {
        DB::table('proveedores')->insert([
            'nit' => $request->nit,
            'razon_social' => $request->razon_social,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'estado' => $request->estado,
        ]);

        return redirect()->route('proveedores.index')->with('alert', ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Proveedor registrado correctamente.']);
    }

    public function update(Request $request, $id)
    {
        DB::table('proveedores')->where('id_proveedor', $request->id_proveedor)->update([
            'nit' => $request->nit,
            'razon_social' => $request->razon_social,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'estado' => $request->estado,
        ]);

        return redirect()->route('proveedores.index')->with('alert', ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Proveedor actualizado correctamente.']);
    }

    public function destroy($id)
    {
        DB::table('proveedores')->where('id_proveedor', $id)->delete();

        return redirect()->route('proveedores.index')->with('alert', ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Proveedor eliminado correctamente.']);
    }
}
