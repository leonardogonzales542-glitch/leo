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

    public function checkDuplicate(Request $request)
    {
        $field = $request->input('field');
        $value = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('value')));
        $excludeId = $request->input('exclude_id');

        if (! in_array($field, ['nombre', 'numero_documento', 'telefono', 'email', 'direccion'], true) || $value === '') {
            return response()->json(['exists' => false]);
        }

        $query = DB::table('clientes')->where($field, $value);
        if ($excludeId) {
            $query->where('id_cliente', '!=', $excludeId);
        }

        $exists = $query->exists();

        $nombresCampos = [
            'nombre' => 'este nombre',
            'numero_documento' => 'esta CC / documento',
            'telefono' => 'este número de teléfono',
            'email' => 'este correo electrónico',
            'direccion' => 'esta dirección',
        ];

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Ya existe un cliente registrado con '.($nombresCampos[$field] ?? $field).'.' : '',
        ]);
    }

    public function store(Request $request)
    {
        $nombre = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('nombre')));
        $numero_documento = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('numero_documento')));
        $telefono = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('telefono')));
        $email = trim((string) $request->input('email'));
        $direccion = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('direccion')));

        $request->merge([
            'nombre' => $nombre,
            'numero_documento' => $numero_documento,
            'telefono' => $telefono !== '' ? $telefono : null,
            'email' => $email !== '' ? $email : null,
            'direccion' => $direccion !== '' ? $direccion : null,
        ]);

        $request->validate([
            'nombre' => 'required|string|max:120|unique:clientes,nombre',
            'tipo_documento' => 'required|string|max:30',
            'numero_documento' => 'required|string|max:30|unique:clientes,numero_documento',
            'telefono' => 'nullable|string|max:30|unique:clientes,telefono',
            'email' => 'nullable|email|max:100|unique:clientes,email',
            'direccion' => 'nullable|string|max:150|unique:clientes,direccion',
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.unique' => 'Error: Ya existe un cliente registrado con este nombre.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required' => 'El número de CC / documento es obligatorio.',
            'numero_documento.unique' => 'Error: Ya existe un cliente registrado con este número de CC / documento.',
            'telefono.unique' => 'Error: Ya existe un cliente registrado con este número de teléfono.',
            'email.email' => 'Por favor ingrese un correo electrónico válido.',
            'email.unique' => 'Error: Ya existe un cliente registrado con este correo electrónico.',
            'direccion.unique' => 'Error: Ya existe un cliente registrado con esta dirección.',
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
        $id_cliente = $request->input('id_cliente', $id);
        $nombre = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('nombre')));
        $numero_documento = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('numero_documento')));
        $telefono = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('telefono')));
        $email = trim((string) $request->input('email'));
        $direccion = trim((string) preg_replace('/\s+/', ' ', (string) $request->input('direccion')));

        $request->merge([
            'id_cliente' => $id_cliente,
            'nombre' => $nombre,
            'numero_documento' => $numero_documento,
            'telefono' => $telefono !== '' ? $telefono : null,
            'email' => $email !== '' ? $email : null,
            'direccion' => $direccion !== '' ? $direccion : null,
        ]);

        $request->validate([
            'nombre' => 'required|string|max:120|unique:clientes,nombre,'.$id_cliente.',id_cliente',
            'tipo_documento' => 'required|string|max:30',
            'numero_documento' => 'required|string|max:30|unique:clientes,numero_documento,'.$id_cliente.',id_cliente',
            'telefono' => 'nullable|string|max:30|unique:clientes,telefono,'.$id_cliente.',id_cliente',
            'email' => 'nullable|email|max:100|unique:clientes,email,'.$id_cliente.',id_cliente',
            'direccion' => 'nullable|string|max:150|unique:clientes,direccion,'.$id_cliente.',id_cliente',
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.unique' => 'Error: Ya existe otro cliente registrado con este nombre.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required' => 'El número de CC / documento es obligatorio.',
            'numero_documento.unique' => 'Error: Ya existe otro cliente registrado con este número de CC / documento.',
            'telefono.unique' => 'Error: Ya existe otro cliente registrado con este número de teléfono.',
            'email.email' => 'Por favor ingrese un correo electrónico válido.',
            'email.unique' => 'Error: Ya existe otro cliente registrado con este correo electrónico.',
            'direccion.unique' => 'Error: Ya existe otro cliente registrado con esta dirección.',
        ]);

        $estado = in_array((string) $request->input('estado'), ['1', 'Activo', 'on', 'true'], true) ? 1 : 0;

        DB::table('clientes')->where('id_cliente', $id_cliente)->update([
            'nombre' => $request->nombre,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'estado' => $estado,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('clientes')->where('id_cliente', $id)->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
