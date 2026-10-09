<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaborController extends Controller
{
    public function index()
    {
        return view('admin.registrar_labor');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'lote' => 'required|string|max:255',
            'trabajo_realizado' => 'required|string',
            'trabajadores' => 'required|string',
            'valor_original' => 'nullable|string',
            'valor_calculado' => 'required|numeric',
        ]);

        return redirect()->route('labores.index')->with('success', 'Labor registrada correctamente.');
    }
}
