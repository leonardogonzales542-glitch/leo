<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CuponController extends Controller
{
    public function index(): View
    {
        $cupones = Cupon::orderBy('id_cupon', 'desc')->get();

        return view('admin.cupones', compact('cupones'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('cupones.index');
    }

    public function show(int $id): RedirectResponse
    {
        return redirect()->route('cupones.index');
    }

    public function edit(int $id): RedirectResponse
    {
        return redirect()->route('cupones.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo',
            'descuento' => 'required|numeric|min:0',
            'tipo' => 'required|in:Porcentaje,Fijo',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'nullable|boolean',
        ]);

        $validated['estado'] = $request->boolean('estado');

        Cupon::create($validated);

        return redirect()->route('cupones.index')
            ->with('success', 'Cupón creado correctamente.');
    }

    public function update(Request $request, Cupon $cupone): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo,'.$cupone->id_cupon.',id_cupon',
            'descuento' => 'required|numeric|min:0',
            'tipo' => 'required|in:Porcentaje,Fijo',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'nullable|boolean',
        ]);

        $validated['estado'] = $request->boolean('estado');

        $cupone->update($validated);

        return redirect()->route('cupones.index')
            ->with('success', 'Cupón actualizado correctamente.');
    }

    public function destroy(Cupon $cupone): RedirectResponse
    {
        $cupone->delete();

        return redirect()->route('cupones.index')
            ->with('success', 'Cupón eliminado correctamente.');
    }
}
