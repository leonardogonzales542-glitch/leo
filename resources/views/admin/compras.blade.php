@extends('layouts.app')
@section('title', 'Gestión de Compras a Proveedores')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Compras a Proveedores</h3>
            <span class="text-muted small">Panel principal e historial de compras (Entradas)</span>
        </div>
        <div>
            <a href="{{ route('compras.create') }}" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-plus me-2"></i>Nueva Compra
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Historial de Compras -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="rounded-start">ID</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Nº Documento</th>
                            <th>Total</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $compra->id_entrada }}</td>
                                <td>{{ date('d/m/Y h:i A', strtotime($compra->fecha)) }}</td>
                                <td>
                                    <div class="fw-medium">{{ $compra->proveedor->razon_social ?? 'Sin proveedor' }}</div>
                                </td>
                                <td>{{ $compra->numero_documento ?? '-' }}</td>
                                <td class="fw-bold text-dark">${{ number_format($compra->total, 2) }}</td>
                                <td class="small text-muted">{{ $compra->usuario->usuario ?? 'Admin' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay compras registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
