<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    public function index()
    {
        $devoluciones = \Illuminate\Support\Facades\DB::table('devoluciones')
            ->leftJoin('ventas', 'devoluciones.id_venta', '=', 'ventas.id_venta')
            ->select('devoluciones.*')
            ->orderByDesc('fecha')
            ->get();
            
        return view('admin.devoluciones', compact('devoluciones'));
    }
}
