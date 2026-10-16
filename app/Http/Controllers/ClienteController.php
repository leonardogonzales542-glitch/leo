<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = DB::table('clientes')->orderByDesc('id_cliente')->get();

        return view('admin.clientes', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:clientes,nombre',
            'numero_documento' => 'required|string|max:50|unique:clientes,numero_documento',
        ], [
            'nombre.unique' => 'Error: Ya existe un cliente registrado con ese nombre.',
            'numero_documento.unique' => 'Error: Ya existe un cliente registrado con ese documento (CC).',
        ]);

        DB::table('clientes')->insert([
            'nombre' => $request->nombre,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'estado' => 1,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:clientes,nombre,'.$request->id_cliente.',id_cliente',
            'numero_documento' => 'required|string|max:50|unique:clientes,numero_documento,'.$request->id_cliente.',id_cliente',
        ], [
            'nombre.unique' => 'Error: Ya existe otro cliente registrado con ese nombre.',
            'numero_documento.unique' => 'Error: Ya existe otro cliente registrado con ese documento (CC).',
        ]);

        DB::table('clientes')->where('id_cliente', $request->id_cliente)->update([
            'nombre' => $request->nombre,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('clientes')->where('id_cliente', $id)->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
