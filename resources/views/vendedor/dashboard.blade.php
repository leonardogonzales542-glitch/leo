@extends('layouts.app')

@section('title', 'Panel del Vendedor | AgriStock')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
        <div>
            <h1 class="h3 fw-bold mb-1">Panel del vendedor</h1>
            <p class="text-muted mb-0">Bienvenido, {{ auth()->user()->usuario ?? 'Vendedor' }}.</p>
        </div>

        <nav class="d-flex flex-wrap gap-2" aria-label="Accesos rápidos del vendedor">
            <a href="{{ route('ventas.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>Nueva venta
            </a>
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-truck me-2" aria-hidden="true"></i>Pedidos
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-boxes-stacked me-2" aria-hidden="true"></i>Inventario
            </a>
        </nav>
    </div>
@endsection
