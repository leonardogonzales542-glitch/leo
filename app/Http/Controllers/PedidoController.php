<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = \Illuminate\Support\Facades\DB::table('pedidos')
            ->leftJoin('clientes', 'pedidos.id_cliente', '=', 'clientes.id_cliente')
            ->select('pedidos.*', 'clientes.nombre as cliente')
            ->orderByDesc('fecha')
            ->get();
        return view('admin.pedidos', compact('pedidos'));
    }
}
