<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = \App\Models\Usuario::all();
        $roles = \App\Models\Rol::all();
        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'];
        
        return view('admin.gusuarios', compact('usuarios', 'roles', 'estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:6',
            'id_rol' => 'required'
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['estado'] = 'Activo';
        
        \App\Models\Usuario::create($data);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function update(Request $request, $id)
    {
        $usuario = \App\Models\Usuario::findOrFail($id);
        $data = $request->validate([
            'usuario' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,'.$id.',id_usuario',
            'id_rol' => 'required',
            'estado' => 'required'
        ]);

        if ($request->filled('password')) {
            $data['password'] = password_hash($request->password, PASSWORD_DEFAULT);
        }

        $usuario->update($data);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy($id)
    {
        \App\Models\Usuario::destroy($id);
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
