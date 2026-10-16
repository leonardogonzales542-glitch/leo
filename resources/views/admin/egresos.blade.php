@extends('layouts.app')
@section('title', 'Gestión de Gastos')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1100px; margin: 0 auto; width: 100%;">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Gastos Operativos</h3>
            <span class="text-muted small">Registro de egresos y salidas de dinero</span>
        </div>
        <a href="{{ route('egresos.create') }}" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium"
           style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none;">
            <i class="fa-solid fa-plus me-2"></i>Registrar Gasto
        </a>
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

    <!-- Resumen total -->
    <div class="card border-0 shadow-sm rounded-4 p-3" style="border-left: 5px solid #dc3545 !important;">
        <div class="card-body py-2">
            <h6 class="text-muted fw-bold text-uppercase small mb-1">Total Gastos Registrados</h6>
            <h3 class="fw-bold text-danger mb-0">${{ number_format($totalGastos, 2) }}</h3>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            @if ($egresos->count() > 0)
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted" style="font-weight:600;font-size:0.8rem;">#</th>
                            <th class="px-4 py-3 text-muted" style="font-weight:600;font-size:0.8rem;">FECHA</th>
                            <th class="px-4 py-3 text-muted" style="font-weight:600;font-size:0.8rem;">CONCEPTO</th>
                            <th class="px-4 py-3 text-muted" style="font-weight:600;font-size:0.8rem;">MÉTODO DE PAGO</th>
                            <th class="px-4 py-3 text-muted text-end" style="font-weight:600;font-size:0.8rem;">MONTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($egresos as $egreso)
                            <tr class="border-bottom">
                                <td class="px-4 py-3 text-muted" style="font-size:0.85rem;">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-dark" style="font-size:0.85rem;">
                                    {{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-dark fw-semibold" style="font-size:0.9rem;">{{ $egreso->concepto }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-1" style="font-size:0.78rem;">
                                        {{ $egreso->metodo_pago }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end fw-bold text-danger" style="font-size:0.95rem;">
                                    ${{ number_format($egreso->monto, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-file-invoice-dollar text-muted mb-3" style="font-size:2.5rem;"></i>
                    <p class="text-muted mb-3">No hay gastos registrados todavía.</p>
                    <a href="{{ route('egresos.create') }}" class="btn btn-outline-primary rounded-3">
                        <i class="fa-solid fa-plus me-2"></i>Registrar primer gasto
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
