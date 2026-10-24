<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class VendedorController extends Controller
{
    public function index(): View
    {
        $vendedores = Usuario::where('id_rol', 2)
            ->orderBy('id_usuario', 'desc')
            ->get();

        return view('admin.vendedores', compact('vendedores'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('vendedores.index');
    }

    public function show(int $id): RedirectResponse
    {
        return redirect()->route('vendedores.index');
    }

    public function edit(int $id): RedirectResponse
    {
        return redirect()->route('vendedores.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:usuarios,email',
            'password' => 'required|string|min:6',
        ]);

        Usuario::create([
            'usuario' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_rol' => 2,
            'estado' => 'Activo',
        ]);

        return redirect()->route('vendedores.index')
            ->with('success', 'Vendedor registrado correctamente.');
    }

    public function update(Request $request, int $vendedore): RedirectResponse
    {
        $vend = Usuario::where('id_usuario', $vendedore)->where('id_rol', 2)->firstOrFail();

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:usuarios,email,'.$vend->id_usuario.',id_usuario',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'usuario' => $validated['nombre'],
            'email' => $validated['email'],
            'estado' => $request->has('estado') ? 'Activo' : 'Inactivo',
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $vend->update($data);

        return redirect()->route('vendedores.index')
            ->with('success', 'Vendedor actualizado correctamente.');
    }

    public function destroy(int $vendedore): RedirectResponse
    {
        $vend = Usuario::where('id_usuario', $vendedore)->where('id_rol', 2)->firstOrFail();
        $vend->delete();

        return redirect()->route('vendedores.index')
            ->with('success', 'Vendedor eliminado correctamente.');
    }
}
