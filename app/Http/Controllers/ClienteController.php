<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = \Illuminate\Support\Facades\DB::table('clientes')->orderByDesc('id_cliente')->get();
        return view('admin.clientes', compact('clientes'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\DB::table('clientes')->insert([
            'nombre' => $request->nombre,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'estado' => 1
        ]);
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        \Illuminate\Support\Facades\DB::table('clientes')->where('id_cliente', $request->id_cliente)->update([
            'nombre' => $request->nombre,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'estado' => $request->has('estado') ? 1 : 0
        ]);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        \Illuminate\Support\Facades\DB::table('clientes')->where('id_cliente', $id)->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
