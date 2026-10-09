<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgresoController extends Controller
{
    public function index()
    {
        return view('admin.registrar_labor'); // Will be renamed later or just keep using this view for now
    }

    public function create()
    {
        return view('admin.registrar_labor');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'concepto' => 'required|string|max:150',
            'monto' => 'required|numeric',
        ]);

        DB::table('egresos')->insert([
            'fecha' => $validated['fecha'],
            'concepto' => $validated['concepto'],
            'monto' => $validated['monto'],
            'metodo_pago' => $validated['metodo_pago'],
        ]);

        return redirect()->route('egresos.index')->with('success', 'Gasto registrado correctamente.');
    }
}
