<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    public function index(): View
    {
        $config = Configuracion::first();

        return view('admin.configuracion', compact('config'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_empresa' => 'required|string|max:150',
            'nit' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
        ]);

        Configuracion::updateOrCreate([], $validated);

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración guardada correctamente.');
    }
}
