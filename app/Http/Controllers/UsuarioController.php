<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->orderBy('id_usuario', 'asc')->get();
        $roles = Rol::all();
        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'];

        return view('admin.gusuarios', compact('usuarios', 'roles', 'estados'));
    }

    public function create()
    {
        return redirect()->route('usuarios.index');
    }

    public function show($id)
    {
        return redirect()->route('usuarios.index');
    }

    public function edit($id)
    {
        return redirect()->route('usuarios.index');
    }

    public function store(Request $request)
    {
        $id_rol = $request->input('id_rol', $request->input('rol'));
        $request->merge(['id_rol' => $id_rol]);

        $data = $request->validate([
            'usuario' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'id_rol' => 'required',
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['estado'] = $request->input('estado', 'Activo');

        Usuario::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $id_rol = $request->input('id_rol', $request->input('rol', $usuario->id_rol));
        $request->merge(['id_rol' => $id_rol]);

        $data = $request->validate([
            'usuario' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,'.$id.',id_usuario',
            'id_rol' => 'required',
            'estado' => 'required',
        ]);

        if ($request->filled('password')) {
            $data['password'] = password_hash($request->password, PASSWORD_DEFAULT);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        Usuario::destroy($id);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
